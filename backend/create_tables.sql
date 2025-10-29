-- Clients table
CREATE TABLE IF NOT EXISTS clients (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) DEFAULT NULL,
    email varchar(255) DEFAULT NULL,
    phone varchar(255) DEFAULT NULL,
    comment text,
    client_wishes text,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY clients_email_unique (email),
    UNIQUE KEY clients_phone_unique (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services table
CREATE TABLE IF NOT EXISTS services (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    description text,
    image_path varchar(255) DEFAULT NULL,
    site_title varchar(255) DEFAULT NULL,
    site_description text,
    is_active tinyint(1) NOT NULL DEFAULT '1',
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bundles table
CREATE TABLE IF NOT EXISTS bundles (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    description text,
    price decimal(10,2) DEFAULT NULL,
    site_title varchar(255) DEFAULT NULL,
    site_subtitle varchar(255) DEFAULT NULL,
    site_description text,
    gallery json DEFAULT NULL,
    show_price_on_site tinyint(1) NOT NULL DEFAULT '0',
    is_active tinyint(1) NOT NULL DEFAULT '1',
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Options table
CREATE TABLE IF NOT EXISTS options (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    description text,
    price decimal(10,2) DEFAULT NULL,
    price_per_person tinyint(1) NOT NULL DEFAULT '0',
    price_per_day tinyint(1) NOT NULL DEFAULT '0',
    price_multiplier decimal(5,2) DEFAULT NULL,
    image_path varchar(255) DEFAULT NULL,
    show_price_on_site tinyint(1) NOT NULL DEFAULT '0',
    is_active tinyint(1) NOT NULL DEFAULT '1',
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Applications table
CREATE TABLE IF NOT EXISTS applications (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    client_id bigint unsigned NOT NULL,
    service_id bigint unsigned DEFAULT NULL,
    bundle_id bigint unsigned DEFAULT NULL,
    nights int NOT NULL DEFAULT '2',
    status varchar(255) NOT NULL DEFAULT 'new',
    client_wishes text,
    comment text,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY applications_client_id_foreign (client_id),
    KEY applications_service_id_foreign (service_id),
    KEY applications_bundle_id_foreign (bundle_id),
    CONSTRAINT applications_client_id_foreign FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE CASCADE,
    CONSTRAINT applications_service_id_foreign FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE SET NULL,
    CONSTRAINT applications_bundle_id_foreign FOREIGN KEY (bundle_id) REFERENCES bundles (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bundle Service pivot table
CREATE TABLE IF NOT EXISTS bundle_service (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    bundle_id bigint unsigned NOT NULL,
    service_id bigint unsigned NOT NULL,
    created_at timestamp NULL DEFAULT NULL,
    updated_at timestamp NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY bundle_service_bundle_id_service_id_unique (bundle_id,service_id),
    KEY bundle_service_service_id_foreign (service_id),
    CONSTRAINT bundle_service_bundle_id_foreign FOREIGN KEY (bundle_id) REFERENCES bundles (id) ON DELETE CASCADE,
    CONSTRAINT bundle_service_service_id_foreign FOREIGN KEY (service_id) REFERENCES services (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache table
CREATE TABLE IF NOT EXISTS cache (
    key varchar(255) NOT NULL,
    value mediumtext NOT NULL,
    expiration int NOT NULL,
    PRIMARY KEY (key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs table
CREATE TABLE IF NOT EXISTS jobs (
    id bigint unsigned NOT NULL AUTO_INCREMENT,
    queue varchar(255) NOT NULL,
    payload longtext NOT NULL,
    attempts tinyint unsigned NOT NULL,
    reserved_at int unsigned DEFAULT NULL,
    available_at int unsigned NOT NULL,
    created_at int unsigned NOT NULL,
    PRIMARY KEY (id),
    KEY jobs_queue_index (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
