<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

$tituloPagina = 'Surcos | Mercado de Pools';
$descripcionPagina = 'Surcos conecta compradores en pools de compra colectiva con productores que publican lotes de cosecha.';
$paginaActiva = 'marketplace';
$cargarBootstrap = false;
$cargarBootstrapJs = false;
$estilosExtra = ['marketplace.css', 'mapa.css', 'pulido-landing.css'];

$boletinesMercado = [
    ['etiqueta' => 'Pools activos', 'valor' => '3'],
    ['etiqueta' => 'Cosechas publicadas', 'valor' => '18 lotes'],
    ['etiqueta' => 'Provincias activas', 'valor' => '4'],
    ['etiqueta' => 'Proximo cierre', 'valor' => 'Tomates Herrera, 15 May'],
];

$poolsActivos = [
    [
        'id' => 'grupo-geisha-42',
        'origen' => 'Tierras Altas, Chiriqui',
        'producto' => 'Cafe Geisha - Micro-lote #42',
        'imagen' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?w=800&q=80&fit=crop',
        'alt' => 'Granos de cafe Geisha listos para compra grupal en Panama',
        'precio' => 'B/. 0.62',
        'unidad' => '/lb',
        'retail' => 'Retail: B/. 1.10',
        'avance' => 85,
        'personas' => '17/20 personas',
        'cierre' => 'Lunes 18 May - 11:59 PM',
        'critico' => true,
    ],
    [
        'id' => 'grupo-tomates-09',
        'origen' => 'Boquete Organico',
        'producto' => 'Tomates de Herencia - Lote 09',
        'imagen' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?w=800&q=80&fit=crop',
        'alt' => 'Tomates organicos de herencia cultivados en tierras altas de Panama',
        'precio' => 'B/. 0.45',
        'unidad' => '/lb',
        'retail' => 'Retail: B/. 0.85',
        'avance' => 42,
        'personas' => '21/50 personas',
        'cierre' => 'Viernes 15 May - 11:59 PM',
        'critico' => false,
    ],
    [
        'id' => 'grupo-miel-cruda',
        'origen' => 'Bosque Silvestre',
        'producto' => 'Miel Silvestre Artesanal - Cruda',
        'imagen' => 'https://images.unsplash.com/photo-1558642452-9d2a7deb7f62?w=800&q=80&fit=crop',
        'alt' => 'Miel artesanal silvestre natural de bosque panameno',
        'precio' => 'B/. 4.20',
        'unidad' => '/lb',
        'retail' => 'Retail: B/. 8.50',
        'avance' => 98,
        'personas' => '49/50 personas',
        'cierre' => 'Miercoles 20 May - 11:59 PM',
        'critico' => false,
    ],
];

renderizar_vista('parciales/cabecera.php', compact(
    'tituloPagina',
    'descripcionPagina',
    'estilosExtra',
    'cargarBootstrap'
));
renderizar_vista('parciales/navegacion.php', compact('paginaActiva'));
?>

<div class="ticker-wrap ticker-wrap--boletin" aria-label="Boletin del mercado de Surcos">
  <div class="ticker-track">
    <?php for ($repeticion = 0; $repeticion < 2; $repeticion++): ?>
      <?php foreach ($boletinesMercado as $boletin): ?>
        <span class="ticker-item"><?= escapar($boletin['etiqueta']) ?>: <b><?= escapar($boletin['valor']) ?></b></span>
        <span class="ticker-item ticker-separador">/</span>
      <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</div>

<main class="wrap wrap--marketplace landing-pulida">
  <section class="hero hero-inicio" aria-labelledby="titulo-inicio">
    <img alt="Vista aerea de plantaciones en tierras volcanicas de Chiriqui Panama"
      src="https://images.unsplash.com/photo-1501854140801-50d01698950b?w=1600&q=85&fit=crop" />
    <div class="hero-overlay"></div>
    <div class="hero-copy">
      <p class="eyebrow">Mercado de pools agricolas</p>
      <h1 id="titulo-inicio" class="hero-text">Directo del Volcan.</h1>
      <p>Compradores se unen a pools de compra colectiva. Productores publican lotes de cosecha con precio, cupo y cierre claros.</p>
      <div class="hero-actions">
        <a class="btn btn--compact" href="#pools-activos">Ver pools activos</a>
        <a class="btn-outline btn-outline--hero" href="#registro-cosecha">Publicar cosecha</a>
      </div>
    </div>
  </section>

  <section class="resumen-operacion" aria-label="Como funciona Surcos">
    <article>
      <span>01</span>
      <strong>Compradores</strong>
      <p>Revisan precio, progreso y fecha de cierre antes de comprometerse a un pool.</p>
    </article>
    <article>
      <span>02</span>
      <strong>Productores</strong>
      <p>Publican cosechas con origen, volumen, precio grupal y modelo de entrega.</p>
    </article>
    <article>
      <span>03</span>
      <strong>Surcos</strong>
      <p>Organiza demanda antes de mover producto, con menos incertidumbre para todos.</p>
    </article>
  </section>

  <div class="section-title section-title--pulida">
    <h2>Mercado de Pools</h2>
    <span>Gaceta de cosechas - precios y cierres visibles</span>
  </div>

  <input type="checkbox" id="role-toggle" class="role-input">
  <label for="role-toggle" class="role-label role-label--pulida">
    <span class="role-comprador">Comprador</span>
    <span class="role-productor">Productor</span>
  </label>

  <section class="view-comprador" id="pools-activos">
    <div class="section-title section-title--sub">
      <h2>Pools de Compra Activos</h2>
      <span><?= escapar((string) count($poolsActivos)) ?> pools abiertos</span>
    </div>

    <section class="grid grid--pools" data-lista-grupos>
      <?php foreach ($poolsActivos as $pool): ?>
        <article class="card card--pool">
          <div class="card-img">
            <img alt="<?= escapar($pool['alt']) ?>" src="<?= escapar($pool['imagen']) ?>" />
            <div class="card-badge"><?= escapar($pool['origen']) ?></div>
          </div>
          <h3 class="name"><?= escapar($pool['producto']) ?></h3>
          <div class="price-row">
            <span class="price tab"><?= escapar($pool['precio']) ?><small><?= escapar($pool['unidad']) ?></small></span>
            <span class="retail tab"><?= escapar($pool['retail']) ?></span>
          </div>
          <div class="prog">
            <div class="prog-head">
              <span class="pool-cantidad"><?= escapar($pool['personas']) ?></span>
              <span class="pool-porcentaje"><?= escapar((string) $pool['avance']) ?>%</span>
            </div>
            <div class="bar">
              <i class="progress-fill <?= $pool['critico'] ? 'terra' : '' ?>" style="--pool-progress:<?= escapar((string) $pool['avance']) ?>%"></i>
            </div>
          </div>
          <div class="deadline">Cierra: <?= escapar($pool['cierre']) ?></div>
          <a href="<?= escapar(url_para('/pool_detail.php?id=' . $pool['id'])) ?>" class="btn">Comprometerse al Pool</a>
        </article>
      <?php endforeach; ?>
    </section>
  </section>

  <section class="view-productor" id="registro-cosecha">
    <div class="section-title section-title--sub">
      <h2>Registro del Productor</h2>
      <span>publicar lote agricola</span>
    </div>

    <form class="form-cosecha needs-validation form-cosecha--pulida" id="formularioCosecha" method="post" action="<?= escapar(url_para('/')) ?>">
      <?= campo_csrf() ?>
      <h3>Publicar Nueva Cosecha</h3>
      <div class="form-grid">
        <div class="form-campo">
          <label for="productoCosecha">producto</label>
          <input class="form-control" id="productoCosecha" name="producto" type="text"
            placeholder="Ej: Tomates de Herencia" minlength="3" required />
          <div class="invalid-feedback">Escribe el nombre del producto.</div>
        </div>
        <div class="form-campo">
          <label for="variedadCosecha">variedad / lote</label>
          <input class="form-control" id="variedadCosecha" name="variedad" type="text"
            placeholder="Ej: Lote #09 - Organico" minlength="3" required />
          <div class="invalid-feedback">Indica la variedad o codigo del lote.</div>
        </div>
        <div class="form-campo">
          <label for="cantidadCosecha">cantidad disponible (kg)</label>
          <input class="form-control tab" id="cantidadCosecha" name="cantidadKg" type="number" placeholder="500"
            min="25" step="1" required />
          <div class="invalid-feedback">La cantidad minima para publicar es 25 kg.</div>
        </div>
        <div class="form-campo">
          <label for="precioCosecha">precio minimo grupal (B/./kg)</label>
          <input class="form-control tab" id="precioCosecha" name="precioMinimo" type="number" placeholder="4.50"
            min="0.25" step="0.01" required />
          <div class="invalid-feedback">Ingresa un precio valido por kilogramo.</div>
        </div>
        <div class="form-campo">
          <label for="ubicacionCosecha">ubicacion de la finca</label>
          <select class="form-select" id="ubicacionCosecha" name="ubicacion" required>
            <option value="">Selecciona una zona</option>
            <option>Tierras Altas, Chiriqui</option>
            <option>Boquete, Chiriqui</option>
            <option>Volcan, Chiriqui</option>
            <option>Cerro Punta</option>
            <option>Cocle</option>
            <option>Darien</option>
          </select>
          <div class="invalid-feedback">Selecciona la ubicacion de la finca.</div>
        </div>
        <div class="form-campo">
          <label for="ventanaCosecha">ventana de cosecha</label>
          <input class="form-control" id="ventanaCosecha" name="ventana" type="text"
            placeholder="15 May - 30 May 2026" minlength="8" required />
          <div class="invalid-feedback">Indica la ventana estimada de cosecha.</div>
        </div>
      </div>

      <fieldset class="modelo-fieldset">
        <legend>Modelo de Entrega</legend>
        <label class="modelo-radio">
          <input type="radio" name="modeloEntrega" value="Retiro en Nodo" checked />
          <div>
            <p class="modelo-radio-titulo">Retiro en Nodo</p>
            <p class="modelo-radio-desc">El comprador retira en el nodo mas cercano a su provincia</p>
          </div>
        </label>
        <label class="modelo-radio">
          <input type="radio" name="modeloEntrega" value="Envio a Domicilio" />
          <div>
            <p class="modelo-radio-titulo">Envio a Domicilio</p>
            <p class="modelo-radio-desc">Costo adicional B/.3.50 - MRW / servicio del agricultor</p>
          </div>
        </label>
        <label class="modelo-radio">
          <input type="radio" name="modeloEntrega" value="Lote Empresarial" />
          <div>
            <p class="modelo-radio-titulo">Lote Empresarial</p>
            <p class="modelo-radio-desc">Minimo 200 lbs - Precio negociado directo con la finca</p>
          </div>
        </label>
      </fieldset>

      <button class="btn-primary" type="submit" data-accion="publicar-cosecha">Publicar en Marketplace</button>
    </form>
  </section>
</main>

<?php renderizar_vista('parciales/pie.php', compact('cargarBootstrapJs')); ?>
