<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/aplicacion/Arranque.php';

(new ControladorAdmin())->pools();
