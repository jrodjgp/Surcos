<?php
$nombreProductor = $productor['nombre'] ?? 'Productores Surcos';
$nombreCorto = trim((string) preg_replace('/^(Finca|Cooperativa|Apiario)\s+/i', '', (string) $nombreProductor));
$poolHero = $poolPrincipal ?? ($poolsProductor[0] ?? null);
$imagenHero = $poolHero ? imagen_cosecha($poolHero) : url_recurso('img/cosechas/cosecha-generica.jpg');
$avanceHero = $poolHero ? $poolModelo->avance($poolHero) : 0;
$historiasEditoriales = [
    'prod-heredia' => [
        'parrafos' => [
            'En Boquete, Finca Heredia trabaja micro-lotes de Cafe Geisha en las tierras altas de Chiriqui. El proceso honey y los perfiles florales son parte de la identidad del lote, no una nota al margen del producto.',
            'Don Sebastian Heredia publica cada registro con el origen, la variedad y el volumen que el comprador puede revisar antes de comprometerse. La historia de la finca se vuelve concreta cuando el lote tiene una fecha, un precio y un avance visible.',
            'El Micro-lote #42 entra a Surcos con una demanda que ya se puede medir. El pool muestra cuanto falta para el siguiente tramo y deja que mas compradores se sumen sin perder de vista de donde viene el cafe.',
            'Aqui la compra grupal no reemplaza el trabajo de la finca. Lo hace legible para quien compra y le da al productor una señal mas clara antes de mover el producto.',
        ],
        'cita' => 'En un micro-lote, la altura, el proceso y el origen tambien forman parte de lo que el comprador esta eligiendo.',
    ],
    'prod-oasis' => [
        'parrafos' => [
            'Finca Oasis cultiva hortalizas de temporada en suelos volcanicos y clima frio. Desde Tierras Altas, Ana Rodriguez publica lotes que se pueden leer por producto, cantidad, fecha y nodo de retiro.',
            'El pool de Tomates de Herencia lleva esa cosecha a una decision concreta: el comprador ve el precio vigente, el progreso y el volumen que falta para alcanzar el siguiente tramo.',
            'La Lechuga UTP pertenece al mismo registro de trabajo, pero mantiene su propia historia dentro del marketplace. Cada lote conserva su nombre, su origen y su fecha, en lugar de mezclarse con una promesa general de abastecimiento.',
            'Surcos le permite a Finca Oasis abrir demanda antes de mover producto y mostrar con claridad que parte del lote ya tiene compradores interesados.',
        ],
        'cita' => 'Finca Oasis no publica una idea de cosecha: publica lotes concretos, con origen y volumen a la vista.',
    ],
    'prod-bosque' => [
        'parrafos' => [
            'En El Valle, el Apiario Bosque Silvestre trabaja con cosechas pequenas y una trazabilidad ligada a la floracion. La Miel Cruda aparece en Surcos con un origen identificable y una historia que empieza antes de la compra.',
            'Luis Medina publica un producto cuyo valor depende tanto de la calidad como de la claridad del registro. El comprador puede revisar el lote, la fecha de entrega y el modelo de envio antes de confirmar.',
            'El pool de Miel Silvestre Artesanal muestra como una demanda organizada puede acercar una cosecha artesanal a compradores que buscan conocer de donde viene lo que llevan a su mesa.',
            'Cuando el objetivo se alcanza, el sistema conserva el compromiso y el pago simulado como parte del historial. La trazabilidad queda asociada al lote, no solo al momento de la compra.',
        ],
        'cita' => 'La miel conserva su origen cuando el registro cuenta quien la produce, donde nace y como se forma el lote.',
    ],
    'prod-darien' => [
        'parrafos' => [
            'La Cooperativa Darien Cacao trabaja desde Meteti con cacao fermentado de origen unico. El lote llega al marketplace con una identidad propia, vinculada a la cooperativa familiar que lo produce.',
            'Marta Quintero puede publicar el Cacao Crudo con un objetivo de compradores y tramos de precio visibles. Asi, el volumen no queda escondido detras de una cifra final, sino que se construye a medida que el grupo participa.',
            'El pool deja ver el avance antes del compromiso y conserva el origen del producto durante todo el flujo. El comprador sabe que esta entrando a un lote de Darien, no a una categoria generica de cacao.',
            'Para una cooperativa, esa claridad ayuda a ordenar la demanda y a presentar un producto de origen unico sin borrar la escala familiar que lo sostiene.',
        ],
        'cita' => 'El cacao de Meteti entra al pool con su origen al frente, porque la fermentacion y la procedencia tambien explican su valor.',
    ],
    'prod-azuero' => [
        'parrafos' => [
            'Finca Azuero Verde produce una cantidad limitada de aceite Arbequina prensado en frio desde Chitre, Herrera. Carlos Batista publica este lote para compradores que necesitan leer primero el producto, el volumen y la fecha de entrega.',
            'El modelo de Lote Empresarial reconoce que no todos los compradores llegan con la misma escala. En este caso, el registro esta pensado para una compra organizada, con un objetivo claro y un precio que cambia cuando el grupo crece.',
            'El pool de Aceite Arbequina muestra el avance de la demanda y mantiene visible el nodo de retiro. El comprador puede revisar el compromiso antes de confirmar, mientras el productor ve cuanto interes real tiene el lote.',
            'Surcos convierte una produccion limitada en una oferta mas facil de coordinar, sin esconder que el aceite sigue dependiendo del ritmo y la disponibilidad de una finca concreta.',
        ],
        'cita' => 'Un lote limitado tambien puede venderse con claridad cuando el comprador entiende su origen, su escala y el momento de entrega.',
    ],
];
$historiaEditorial = $historiasEditoriales[(string) ($productor['id'] ?? '')] ?? null;
$lineasHistoria = is_array($historiaEditorial) && !empty($historiaEditorial['parrafos'])
    ? $historiaEditorial['parrafos']
    : array_values(array_filter([
    $productor['historia'] ?? null,
    isset($productor['zona'], $productor['provincia'], $productor['responsable'])
        ? 'Desde ' . $productor['zona'] . ', ' . $productor['provincia'] . ', ' . $productor['responsable'] . ' organiza lotes para vender con menos intermediarios y con demanda visible antes de mover producto.'
        : null,
    $poolHero
        ? 'Su pool activo de ' . $poolHero['producto'] . ' muestra precio grupal, cupo, origen, nodo y cierre antes de que el comprador confirme un compromiso.'
        : 'Cuando publica un lote, Surcos lo convierte en una oportunidad clara: origen, volumen, precio y fecha de cierre en un solo registro.',
]));
$citaHistoria = is_array($historiaEditorial) && !empty($historiaEditorial['cita'])
    ? $historiaEditorial['cita']
    : 'Cada pool convierte una cosecha en una venta mas predecible para el productor y mas clara para el comprador.';
if (strtolower(trim((string) ($poolHero['producto'] ?? ''))) === 'lechuga utp') {
    $citaHistoria = 'Cada pool convierte una cosecha en una venta mas predecible para el productor y mas clara para el comprador.';
}
?>

<main class="pagina-historias">
  <?php if (!empty($errorDatos)): ?>
    <div class="wrap aviso-bd"><?= escapar($errorDatos) ?></div>
  <?php endif; ?>

  <?php if (!$productor): ?>
    <section class="wrap estado-vacio">
      <strong>No hay productores disponibles.</strong>
      <p>Importa las semillas demo para ver historias conectadas a pools.</p>
    </section>
  <?php else: ?>
    <section class="story-hero historia-hero" aria-labelledby="titulo-historia">
      <img src="<?= escapar($imagenHero) ?>" alt="<?= escapar($nombreProductor . ' en Surcos') ?>" />
      <div class="grad"></div>
      <div class="content">
        <div>
          <p class="label">Historia de productor - <?= escapar($productor['provincia']) ?></p>
          <h1 id="titulo-historia"><?= escapar($nombreCorto) ?>.</h1>
        </div>
        <a class="historia-hero-link" href="#pools-productor">Ver pools</a>
      </div>
    </section>

    <section class="editorial historia-editorial">
      <aside class="sidebar-meta">
        <div class="block">
          <h2 class="lbl">Responsable</h2>
          <p class="val"><?= escapar($productor['responsable']) ?></p>
        </div>
        <div class="block">
          <h2 class="lbl">Ubicacion</h2>
          <p class="val"><?= escapar($productor['zona']) ?><br><?= escapar($productor['provincia']) ?></p>
        </div>
        <div class="block">
          <h2 class="lbl">Especialidad</h2>
          <p class="val"><?= escapar($productor['especialidad']) ?></p>
        </div>
      </aside>

      <article class="article">
        <?php foreach ($lineasHistoria as $indice => $parrafo): ?>
          <p class="<?= $indice === 0 ? 'dropcap' : '' ?>"><?= escapar($parrafo) ?></p>
        <?php endforeach; ?>
        <blockquote class="bq">
          <p>"<?= escapar($citaHistoria) ?>"</p>
        </blockquote>
      </article>

      <aside class="sidebar-detail">
        <div class="batch-card">
          <h2 class="lbl">Registro activo</h2>
          <?php if ($poolHero): ?>
            <div class="batch-row"><span>Pool</span><span><?= escapar($poolHero['producto']) ?></span></div>
            <div class="batch-row"><span>Precio vigente</span><span class="terra"><?= escapar(dinero($poolHero['precio_vigente'])) ?></span></div>
            <div class="batch-row"><span>Avance</span><span><?= escapar($poolHero['personas_actuales'] . '/' . $poolHero['personas_objetivo']) ?> personas</span></div>
            <div class="mini-bar"><i style="width:<?= escapar((string) $avanceHero) ?>%"></i></div>
            <a class="historia-mini-cta" href="<?= escapar(url_para('/pool.php?id=' . $poolHero['id'])) ?>">Ver detalle del pool</a>
          <?php else: ?>
            <p class="historia-vacia">Este productor no tiene pools activos en este momento.</p>
          <?php endif; ?>
        </div>
        <div class="sidebar-img">
          <img src="<?= escapar($imagenHero) ?>" alt="<?= escapar('Producto de ' . $nombreProductor) ?>" />
        </div>
      </aside>
    </section>

    <section class="photo-strip historia-tira" aria-label="Productos y registros de <?= escapar($nombreProductor) ?>">
      <?php
      $galeria = array_slice($poolsProductor, 0, 3);
      if (empty($galeria) && $poolHero) {
          $galeria = [$poolHero];
      }
      while (count($galeria) < 3) {
          $galeria[] = ['producto' => $productor['especialidad'], 'origen' => $productor['zona'], 'categoria' => '', 'id' => ''];
      }
      ?>
      <?php foreach ($galeria as $item): ?>
        <figure>
          <img src="<?= escapar(imagen_cosecha($item)) ?>" alt="<?= escapar(($item['producto'] ?? 'Cosecha') . ' de ' . $nombreProductor) ?>" />
          <figcaption><?= escapar(($item['producto'] ?? $productor['especialidad']) . ' - ' . ($item['origen'] ?? $productor['zona'])) ?></figcaption>
        </figure>
      <?php endforeach; ?>
    </section>

    <section class="productores-relacionados">
      <div class="productores-relacionados-inner">
        <h2>Mas historias</h2>
        <div class="productores-relacionados-grid">
          <a class="productor-relacionado activo" href="<?= escapar(url_para('/historias_productor.php?productor=' . $productor['id'])) ?>">
            <span><?= escapar($productor['provincia']) ?></span>
            <strong><?= escapar($productor['nombre']) ?></strong>
            <small><?= escapar($productor['especialidad']) ?></small>
          </a>
          <?php foreach ($relacionados as $relacionado): ?>
            <a class="productor-relacionado" href="<?= escapar(url_para('/historias_productor.php?productor=' . $relacionado['id'])) ?>">
              <span><?= escapar($relacionado['provincia']) ?></span>
              <strong><?= escapar($relacionado['nombre']) ?></strong>
              <small><?= escapar($relacionado['especialidad']) ?></small>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <section class="shop" id="pools-productor">
      <div class="shop-inner">
        <div class="shop-header">
          <h2>Pools de <?= escapar($productor['nombre']) ?></h2>
          <a href="<?= escapar(url_para('/')) ?>#pools-activos">Ver mercado completo</a>
        </div>
        <?php if (empty($poolsProductor)): ?>
          <p class="historia-vacia">Este productor no tiene pools activos en este momento.</p>
        <?php else: ?>
          <div class="shop-grid">
            <?php foreach ($poolsProductor as $pool): ?>
              <?php $avance = $poolModelo->avance($pool); ?>
              <article class="shop-card">
                <figure>
                  <img src="<?= escapar(imagen_cosecha($pool)) ?>" alt="<?= escapar($pool['producto'] . ' - ' . $pool['origen']) ?>" />
                </figure>
                <div class="body">
                  <h3 class="sname"><?= escapar($pool['producto']) ?></h3>
                  <p class="lot"><?= escapar($pool['variedad'] . ' - ' . $pool['origen']) ?></p>
                  <div class="sprice tab"><?= escapar(dinero($pool['precio_vigente'])) ?></div>
                  <div class="mini-bar"><i style="width:<?= escapar((string) $avance) ?>%"></i></div>
                  <a class="sbtn" href="<?= escapar(url_para('/pool.php?id=' . $pool['id'])) ?>">Ver pool</a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>
</main>
