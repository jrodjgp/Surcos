<div class="ticker-wrap ticker-wrap--boletin" aria-label="Boletin del mercado de Surcos">
  <div class="ticker-track">
    <?php
    $boletinesMercado = [
        ['etiqueta' => 'Pools activos', 'valor' => (string) ($resumen['pools'] ?? 0)],
        ['etiqueta' => 'Cosechas publicadas', 'valor' => ($resumen['cosechas'] ?? 0) . ' lotes'],
        ['etiqueta' => 'Compradores en pools', 'valor' => (string) ($resumen['compradores_en_pool'] ?? 0)],
        ['etiqueta' => 'Provincias activas', 'valor' => (string) ($resumen['provincias'] ?? 0)],
        ['etiqueta' => 'Proximo cierre', 'valor' => fecha_hora_corta($resumen['proximo_cierre'] ?? null)],
    ];
    ?>
    <?php for ($repeticion = 0; $repeticion < 2; $repeticion++): ?>
      <?php foreach ($boletinesMercado as $boletin): ?>
        <span class="ticker-item"><?= escapar($boletin['etiqueta']) ?>: <b><?= escapar($boletin['valor']) ?></b></span>
        <span class="ticker-item ticker-separador">/</span>
      <?php endforeach; ?>
    <?php endfor; ?>
  </div>
</div>

<main class="wrap wrap--marketplace landing-pulida">
  <?php if (!empty($errorDatos)): ?>
    <div class="aviso-bd"><?= escapar($errorDatos) ?></div>
  <?php endif; ?>
  <?php
    $productorPuedePublicar = !empty($usuarioActual)
        && ($usuarioActual['rol'] ?? '') === 'productor'
        && ($usuarioActual['estado'] ?? '') === 'activo'
        && !empty($productorActual)
        && ($productorActual['estado'] ?? '') === 'activo';
  ?>

  <section class="hero hero-inicio" aria-labelledby="titulo-inicio">
    <img alt="Vista aerea de plantaciones en tierras volcanicas de Chiriqui Panama"
      src="<?= escapar(url_recurso('img/cosechas/hero-volcan.jpg')) ?>" />
    <div class="hero-overlay"></div>
    <div class="hero-copy">
      <p class="eyebrow">Mercado de pools agricolas</p>
      <h1 id="titulo-inicio" class="hero-text">Directo del Volcan.</h1>
      <p>Compradores se unen a pools de compra colectiva. Productores publican lotes de cosecha con precio, cupo y cierre claros.</p>
      <div class="hero-actions">
        <a class="btn btn--compact" href="#pools-activos">Ver pools activos</a>
        <a class="btn-outline btn-outline--hero" href="<?= escapar($productorPuedePublicar ? url_para('/productor/') : '#registro-cosecha') ?>"><?= $productorPuedePublicar ? 'Panel productor' : 'Afiliar productor' ?></a>
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

    <?php if (empty($poolsActivos)): ?>
      <div class="estado-vacio">
        <strong>No hay pools activos cargados.</strong>
        <p>Importa las semillas demo o publica una cosecha desde el registro del productor.</p>
      </div>
    <?php else: ?>
      <section class="grid grid--pools" data-lista-grupos>
        <?php foreach ($poolsActivos as $pool): ?>
          <?php $avance = $poolModelo->avance($pool); ?>
          <article class="card card--pool">
            <div class="card-img">
              <img alt="<?= escapar($pool['producto'] . ' de ' . $pool['origen']) ?>" src="<?= escapar(imagen_cosecha($pool)) ?>" />
              <div class="card-badge"><?= escapar($pool['origen']) ?></div>
            </div>
            <h3 class="name"><?= escapar($pool['producto'] . ' - ' . $pool['variedad']) ?></h3>
            <div class="price-row">
              <span class="price tab"><?= escapar(dinero($pool['precio_vigente'])) ?><small>/<?= escapar($pool['unidad']) ?></small></span>
              <span class="retail tab">Retail: <?= escapar(dinero($pool['precio_mercado'])) ?></span>
            </div>
            <div class="prog">
              <div class="prog-head">
                <span class="pool-cantidad"><?= escapar($pool['personas_actuales'] . '/' . $pool['personas_objetivo']) ?> personas</span>
                <span class="pool-porcentaje"><?= escapar((string) $avance) ?>%</span>
              </div>
              <div class="bar">
                <i class="progress-fill <?= $avance >= 80 ? 'terra' : '' ?>" style="--pool-progress:<?= escapar((string) $avance) ?>%"></i>
              </div>
            </div>
            <div class="deadline">Cierra: <?= escapar(fecha_hora_corta($pool['fecha_cierre'])) ?></div>
            <?php if (!empty($pool['siguiente_tramo'])): ?>
              <div class="tramo-callout">
                Faltan <?= escapar((string) $pool['faltan_siguiente_tramo']) ?> para <?= escapar(dinero($pool['siguiente_tramo']['precio_unitario'])) ?>/<?= escapar($pool['unidad']) ?>
              </div>
            <?php else: ?>
              <div class="tramo-callout">Mejor precio del pool activo</div>
            <?php endif; ?>
            <a class="pool-historia-link" href="<?= escapar(url_para('/historias_productor.php?productor=' . $pool['productor_id'])) ?>">Historia del productor</a>
            <a href="<?= escapar(url_para('/pool.php?id=' . $pool['id'])) ?>" class="btn">Comprometerse al Pool</a>
          </article>
        <?php endforeach; ?>
      </section>
    <?php endif; ?>
  </section>

  <section class="view-productor" id="registro-cosecha">
    <div class="section-title section-title--sub">
      <h2>Registro del Productor</h2>
      <span>publicar lote agricola</span>
    </div>

    <?php if (!$productorPuedePublicar): ?>
      <div class="estado-vacio estado-vacio--accion">
        <?php if (empty($usuarioActual)): ?>
          <strong>Publicar cosechas requiere una cuenta de productor verificada.</strong>
          <p>Surcos revisa cada solicitud para mantener productores, compradores y pools ordenados antes de abrir publicaciones.</p>
          <div class="estado-acciones">
            <a class="btn" href="<?= escapar(url_para('/contacto.php')) ?>">Solicitar afiliacion</a>
            <a class="btn-outline" href="<?= escapar(url_para('/ingreso.php')) ?>">Ya tengo cuenta</a>
          </div>
        <?php elseif (($usuarioActual['rol'] ?? '') !== 'productor'): ?>
          <strong>Tu cuenta actual es de <?= escapar(str_replace('_', ' ', (string) $usuarioActual['rol'])) ?>.</strong>
          <p>Para publicar lotes necesitas una solicitud de productor aprobada y vinculada a una finca o empresa agricola.</p>
          <div class="estado-acciones">
            <a class="btn" href="<?= escapar(url_para('/contacto.php')) ?>">Solicitar perfil productor</a>
            <a class="btn-outline" href="<?= escapar(url_para('/bandeja.php')) ?>">Ir a mi bandeja</a>
          </div>
        <?php else: ?>
          <strong>Tu perfil productor esta en revision.</strong>
          <p>Cuando la cuenta y el productor esten activos, este espacio mostrara el formulario real para publicar cosechas.</p>
          <div class="estado-acciones">
            <a class="btn" href="<?= escapar(url_para('/contacto.php')) ?>">Contactar admin</a>
            <a class="btn-outline" href="<?= escapar(url_para('/salir.php')) ?>">Cerrar sesion</a>
          </div>
        <?php endif; ?>
      </div>
    <?php else: ?>
    <form class="form-cosecha needs-validation form-cosecha--pulida" id="formularioCosecha" method="post" action="<?= escapar(url_para('/')) ?>">
      <?= campo_csrf() ?>
      <h3>Publicar Nueva Cosecha</h3>
      <p class="form-intro">Publicas como <?= escapar($productorActual['nombre']) ?>. El pool quedara ligado a tu historia de productor.</p>
      <div class="form-grid">
        <div class="form-campo">
          <label for="productoCosecha">producto</label>
          <input class="form-control" id="productoCosecha" name="producto" type="text" placeholder="Ej: Tomates de Herencia" minlength="3" required />
          <div class="invalid-feedback">Escribe el nombre del producto.</div>
        </div>
        <div class="form-campo">
          <label for="variedadCosecha">variedad / lote</label>
          <input class="form-control" id="variedadCosecha" name="variedad" type="text" placeholder="Ej: Lote #09 - Organico" minlength="3" required />
          <div class="invalid-feedback">Indica la variedad o codigo del lote.</div>
        </div>
        <div class="form-campo">
          <label for="cantidadCosecha">cantidad disponible (kg)</label>
          <input class="form-control tab" id="cantidadCosecha" name="cantidadKg" type="number" placeholder="500" min="25" step="1" required />
          <div class="invalid-feedback">La cantidad minima para publicar es 25 kg.</div>
        </div>
        <div class="form-campo">
          <label for="precioCosecha">precio minimo grupal (B/./kg)</label>
          <input class="form-control tab" id="precioCosecha" name="precioMinimo" type="number" placeholder="4.50" min="0.25" step="0.01" required />
          <div class="invalid-feedback">Ingresa un precio valido por kilogramo.</div>
        </div>
        <div class="form-campo">
          <label for="categoriaCosecha">categoria</label>
          <select class="form-select" id="categoriaCosecha" name="categoria" required>
            <option value="">Selecciona categoria</option>
            <option value="cafe">Cafe</option>
            <option value="hortalizas">Hortalizas</option>
            <option value="miel">Miel</option>
            <option value="cacao">Cacao</option>
            <option value="aceite">Aceite</option>
            <option value="frutas">Frutas</option>
            <option value="granos">Granos</option>
            <option value="ganaderia">Ganaderia</option>
          </select>
          <div class="invalid-feedback">Selecciona una categoria valida.</div>
        </div>
        <div class="form-campo">
          <label for="unidadCosecha">unidad</label>
          <select class="form-select" id="unidadCosecha" name="unidad" required>
            <option value="kg">Kilogramo</option>
            <option value="lb">Libra</option>
            <option value="caja">Caja</option>
            <option value="botella">Botella</option>
          </select>
          <div class="invalid-feedback">Selecciona una unidad.</div>
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
          <label for="nodoRetiroCosecha">nodo de retiro</label>
          <select class="form-select" id="nodoRetiroCosecha" name="nodoRetiro" required>
            <option value="">Selecciona nodo</option>
            <?php foreach ($nodosRetiro as $nodo): ?>
              <option value="<?= escapar($nodo['id']) ?>"><?= escapar($nodo['provincia'] . ' - ' . $nodo['nombre']) ?></option>
            <?php endforeach; ?>
          </select>
          <div class="invalid-feedback">Selecciona un nodo de retiro activo.</div>
        </div>
        <div class="form-campo">
          <label for="personasObjetivoCosecha">personas objetivo</label>
          <input class="form-control tab" id="personasObjetivoCosecha" name="personasObjetivo" type="number" min="5" step="1" value="20" required />
          <div class="invalid-feedback">El pool necesita al menos 5 personas objetivo.</div>
        </div>
        <div class="form-campo">
          <label for="fechaCierreCosecha">fecha de cierre</label>
          <input class="form-control" id="fechaCierreCosecha" name="fechaCierre" type="date" required />
          <div class="invalid-feedback">Selecciona una fecha futura de cierre.</div>
        </div>
        <div class="form-campo">
          <label for="fechaEntregaCosecha">fecha de entrega</label>
          <input class="form-control" id="fechaEntregaCosecha" name="fechaEntrega" type="date" required />
          <div class="invalid-feedback">La entrega debe ser posterior al cierre.</div>
        </div>
      </div>

      <fieldset class="modelo-fieldset">
        <legend>Modelo de Entrega</legend>
        <label class="modelo-radio" for="modeloRetiroNodo">
          <input id="modeloRetiroNodo" type="radio" name="modeloEntrega" value="Retiro en Nodo" checked />
          <div>
            <p class="modelo-radio-titulo">Retiro en Nodo</p>
            <p class="modelo-radio-desc">El comprador retira en el nodo mas cercano a su provincia</p>
          </div>
        </label>
        <label class="modelo-radio" for="modeloEnvioDomicilio">
          <input id="modeloEnvioDomicilio" type="radio" name="modeloEntrega" value="Envio a Domicilio" />
          <div>
            <p class="modelo-radio-titulo">Envio a Domicilio</p>
            <p class="modelo-radio-desc">Costo adicional simulado para la demo academica</p>
          </div>
        </label>
        <label class="modelo-radio" for="modeloLoteEmpresarial">
          <input id="modeloLoteEmpresarial" type="radio" name="modeloEntrega" value="Lote Empresarial" />
          <div>
            <p class="modelo-radio-titulo">Lote Empresarial</p>
            <p class="modelo-radio-desc">Minimo institucional con precio negociado directo</p>
          </div>
        </label>
      </fieldset>

      <button class="btn-primary" type="submit" data-accion="publicar-cosecha">Publicar en Marketplace</button>
    </form>
    <?php endif; ?>
  </section>
</main>
