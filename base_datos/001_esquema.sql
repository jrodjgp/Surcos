-- Esquema principal de Surcos para PostgreSQL/Supabase.
-- Ejecutar primero en Supabase SQL Editor.

create extension if not exists pgcrypto;

do $$ begin
    create type estado_usuario as enum ('pendiente', 'activo', 'suspendido');
exception when duplicate_object then null;
end $$;

do $$ begin
    create type rol_usuario as enum ('comprador', 'productor', 'empresa', 'aliado_logistico');
exception when duplicate_object then null;
end $$;

do $$ begin
    create type estado_solicitud as enum ('nueva', 'en_revision', 'aprobada', 'rechazada');
exception when duplicate_object then null;
end $$;

do $$ begin
    create type estado_pool as enum ('activo', 'cerrado', 'fallido');
exception when duplicate_object then null;
end $$;

do $$ begin
    create type estado_compromiso as enum ('borrador', 'confirmado', 'cancelado');
exception when duplicate_object then null;
end $$;

do $$ begin
    create type estado_grupo as enum ('pendiente', 'ganado', 'fallido', 'cancelado');
exception when duplicate_object then null;
end $$;

do $$ begin
    create type estado_entrega as enum ('programado', 'transito', 'entregado', 'cancelado');
exception when duplicate_object then null;
end $$;

do $$ begin
    create type estado_pago as enum ('simulado_autorizado', 'simulado_fallido');
exception when duplicate_object then null;
end $$;

create table if not exists administradores (
    id text primary key default gen_random_uuid()::text,
    nombre text not null,
    correo text not null unique,
    clave_hash text not null,
    activo boolean not null default true,
    creado_en timestamptz not null default now(),
    actualizado_en timestamptz not null default now()
);

create table if not exists nodos_retiro (
    id text primary key,
    provincia text not null,
    nombre text not null,
    direccion text,
    activo boolean not null default true,
    creado_en timestamptz not null default now(),
    actualizado_en timestamptz not null default now(),
    unique (provincia, nombre)
);

create table if not exists usuarios (
    id text primary key,
    nombre text not null,
    correo text not null unique,
    clave_hash text not null,
    telefono text,
    rol rol_usuario not null default 'comprador',
    estado estado_usuario not null default 'pendiente',
    provincia text,
    nodo_retiro_id text references nodos_retiro(id),
    iniciales text,
    debe_cambiar_clave boolean not null default false,
    creado_en timestamptz not null default now(),
    actualizado_en timestamptz not null default now()
);

create table if not exists productores (
    id text primary key,
    usuario_id text references usuarios(id),
    nombre text not null,
    responsable text not null,
    provincia text not null,
    zona text not null,
    especialidad text not null,
    historia text,
    estado estado_usuario not null default 'activo',
    creado_en timestamptz not null default now(),
    actualizado_en timestamptz not null default now()
);

create table if not exists solicitudes_contacto (
    id text primary key default gen_random_uuid()::text,
    nombre text not null,
    correo text not null,
    telefono text,
    tipo_usuario rol_usuario not null,
    asunto text not null,
    mensaje text not null,
    acepta_contacto boolean not null default false,
    estado estado_solicitud not null default 'nueva',
    notas_admin text,
    usuario_creado_id text references usuarios(id),
    creada_en timestamptz not null default now(),
    actualizada_en timestamptz not null default now()
);

create table if not exists eventos_solicitud (
    id text primary key default gen_random_uuid()::text,
    solicitud_id text not null references solicitudes_contacto(id) on delete cascade,
    administrador_id text references administradores(id),
    tipo text not null,
    detalle text,
    creado_en timestamptz not null default now()
);

create table if not exists pools (
    id text primary key,
    productor_id text not null references productores(id),
    producto text not null,
    variedad text not null,
    categoria text not null,
    origen text not null,
    imagen_url text,
    precio_mercado numeric(10, 2) not null,
    precio_grupal numeric(10, 2) not null,
    unidad text not null,
    personas_actuales integer not null default 0 check (personas_actuales >= 0),
    personas_objetivo integer not null check (personas_objetivo > 0),
    cantidad_minima numeric(10, 2) not null check (cantidad_minima > 0),
    fecha_cierre timestamptz not null,
    fecha_entrega date not null,
    estado estado_pool not null default 'activo',
    modelo_entrega text not null,
    nodo_retiro_id text references nodos_retiro(id),
    creado_en timestamptz not null default now(),
    actualizado_en timestamptz not null default now()
);

create table if not exists metodos_pago (
    id text primary key,
    usuario_id text not null references usuarios(id) on delete cascade,
    tipo text not null,
    etiqueta text not null,
    ultimos text not null,
    principal boolean not null default false,
    activo boolean not null default true,
    creado_en timestamptz not null default now(),
    actualizado_en timestamptz not null default now()
);

create table if not exists compromisos (
    id text primary key,
    pool_id text not null references pools(id),
    usuario_id text not null references usuarios(id) on delete cascade,
    producto_snapshot text not null,
    origen_snapshot text not null,
    cantidad numeric(10, 2) not null,
    unidad text not null,
    monto numeric(10, 2) not null,
    metodo_pago_id text references metodos_pago(id),
    metodo_pago_etiqueta text,
    estado_compromiso estado_compromiso not null default 'borrador',
    estado_grupo estado_grupo not null default 'pendiente',
    estado_entrega estado_entrega not null default 'programado',
    fecha date not null default current_date,
    fecha_cancelacion date,
    creado_en timestamptz not null default now(),
    actualizado_en timestamptz not null default now()
);

create table if not exists intentos_pago (
    id text primary key default gen_random_uuid()::text,
    usuario_id text not null references usuarios(id) on delete cascade,
    compromiso_id text references compromisos(id) on delete set null,
    metodo_pago_id text references metodos_pago(id) on delete set null,
    referencia text not null unique,
    monto numeric(10, 2) not null,
    marca text not null,
    ultimos text not null,
    estado estado_pago not null,
    autorizacion_simulada text,
    creado_en timestamptz not null default now()
);

create table if not exists actividad (
    id text primary key,
    usuario_id text references usuarios(id) on delete cascade,
    tipo text not null,
    texto text not null,
    fecha date not null,
    creado_en timestamptz not null default now()
);

create unique index if not exists metodos_pago_principal_unico
    on metodos_pago(usuario_id)
    where principal = true and activo = true;

create unique index if not exists compromisos_borrador_unico
    on compromisos(pool_id, usuario_id)
    where estado_compromiso = 'borrador';

create index if not exists solicitudes_contacto_estado_idx on solicitudes_contacto(estado);
create index if not exists pools_estado_idx on pools(estado);
create index if not exists compromisos_usuario_idx on compromisos(usuario_id, estado_compromiso);
create index if not exists actividad_usuario_idx on actividad(usuario_id, fecha desc);

create or replace function establecer_actualizado_en()
returns trigger as $$
begin
    new.actualizado_en = now();
    return new;
end;
$$ language plpgsql;

drop trigger if exists administradores_actualizado_en on administradores;
create trigger administradores_actualizado_en
before update on administradores
for each row execute function establecer_actualizado_en();

drop trigger if exists nodos_retiro_actualizado_en on nodos_retiro;
create trigger nodos_retiro_actualizado_en
before update on nodos_retiro
for each row execute function establecer_actualizado_en();

drop trigger if exists usuarios_actualizado_en on usuarios;
create trigger usuarios_actualizado_en
before update on usuarios
for each row execute function establecer_actualizado_en();

drop trigger if exists productores_actualizado_en on productores;
create trigger productores_actualizado_en
before update on productores
for each row execute function establecer_actualizado_en();

drop trigger if exists solicitudes_contacto_actualizada_en on solicitudes_contacto;
create trigger solicitudes_contacto_actualizada_en
before update on solicitudes_contacto
for each row execute function establecer_actualizado_en();

drop trigger if exists pools_actualizado_en on pools;
create trigger pools_actualizado_en
before update on pools
for each row execute function establecer_actualizado_en();

drop trigger if exists metodos_pago_actualizado_en on metodos_pago;
create trigger metodos_pago_actualizado_en
before update on metodos_pago
for each row execute function establecer_actualizado_en();

drop trigger if exists compromisos_actualizado_en on compromisos;
create trigger compromisos_actualizado_en
before update on compromisos
for each row execute function establecer_actualizado_en();

