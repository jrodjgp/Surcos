<?php

declare(strict_types=1);

require dirname(__DIR__) . '/aplicacion/Arranque.php';

(new ControladorPool())->detalle();
