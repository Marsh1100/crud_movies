<?php
require_once __DIR__ .'/database/config.php';
require_once __DIR__ .'/database/databaseConector.php';

require_once __DIR__.'/app/interfaces/ValidatorInterface.php';
require_once __DIR__.'/app/interfaces/MovieInterface.php';
require_once __DIR__.'/app/interfaces/CountryInterface.php';
require_once __DIR__.'/app/interfaces/GenreInterface.php';


require_once __DIR__ . '/app/exceptions/ValidationException.php';
require_once __DIR__ . '/app/exceptions/DataException.php';

require_once __DIR__.'/app/validators/MovieValidator.php';


require_once __DIR__.'/app/controllers/MovieController.php';
require_once __DIR__.'/app/controllers/CountryController.php';
require_once __DIR__.'/app/controllers/GenreController.php';


require_once __DIR__ . '/app/services/MovieService.php';
require_once __DIR__ . '/app/services/CountryService.php';
require_once __DIR__ . '/app/services/GenreService.php';


require_once __DIR__ . '/app/repositories/MovieRepository.php';
require_once __DIR__ . '/app/repositories/GenreRepository.php';
require_once __DIR__ . '/app/repositories/StaffRepository.php';
require_once __DIR__ . '/app/repositories/CountryRepository.php';
require_once __DIR__ . '/app/repositories/MovieGenreRepository.php';
require_once __DIR__ . '/app/repositories/MovieStaffRepository.php';






