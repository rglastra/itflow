<?php

/**
 * Mollie API Client Initialization
 * Manual autoloader for Mollie SDK without Composer
 */

// Define base directory
define('MOLLIE_BASE_DIR', __DIR__);

// Core client
require_once MOLLIE_BASE_DIR . '/src/MollieApiClient.php';
require_once MOLLIE_BASE_DIR . '/src/Config.php';
require_once MOLLIE_BASE_DIR . '/src/CompatibilityChecker.php';

// HTTP Client
require_once MOLLIE_BASE_DIR . '/src/Http/Request.php';
require_once MOLLIE_BASE_DIR . '/src/Http/Response.php';
require_once MOLLIE_BASE_DIR . '/src/Http/Adapter/AdapterInterface.php';
require_once MOLLIE_BASE_DIR . '/src/Http/Adapter/Curl.php';

// Contracts
require_once MOLLIE_BASE_DIR . '/src/Contracts/ResourceFactoryContract.php';

// Endpoint Collections
require_once MOLLIE_BASE_DIR . '/src/EndpointCollection/EndpointCollectionInterface.php';
require_once MOLLIE_BASE_DIR . '/src/EndpointCollection/EndpointAbstract.php';
require_once MOLLIE_BASE_DIR . '/src/EndpointCollection/PaymentEndpoint.php';

// Resources
require_once MOLLIE_BASE_DIR . '/src/Resources/ResourceInterface.php';
require_once MOLLIE_BASE_DIR . '/src/Resources/BaseResource.php';
require_once MOLLIE_BASE_DIR . '/src/Resources/CursorCollection.php';
require_once MOLLIE_BASE_DIR . '/src/Resources/Payment.php';

// Traits
require_once MOLLIE_BASE_DIR . '/src/Traits/HasCurrency.php';

// Types
require_once MOLLIE_BASE_DIR . '/src/Types/PaymentStatus.php';

// Exceptions
require_once MOLLIE_BASE_DIR . '/src/Exceptions/ApiException.php';

// Factories
require_once MOLLIE_BASE_DIR . '/src/Factories/ResourceFactory.php';

?>
