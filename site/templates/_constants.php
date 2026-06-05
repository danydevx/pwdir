<?php
/**
 * Directorio Constants
 *
 * Configuración centralizada de templates, campos y páginas.
 * Si se elimina o recrea algo, solo cambiar aquí.
 */

namespace ProcessWire;

defined('PROCESSWIRE') || die;

/**
 * Template names
 */
const TPL_LISTING = 'listing';
const TPL_LISTING_CATEGORY = 'listing-category';
const TPL_BASIC_PAGE = 'basic-page';
const TPL_LOCATIONS = 'locations';
const TPL_COUNTRY = 'country';
const TPL_STATE = 'state';
const TPL_REGION = 'region';
const TPL_TERM = 'term';
const TPL_CATEGORIES = 'categories';

/**
 * Field names
 */
const FLD_REGION = 'fld_region';
const FLD_CATEGORY = 'fld_category';
const FLD_FEATURES = 'fld_features';
const FLD_EVENT_TYPES = 'fld_event_types';
const FLD_SERVICES = 'fld_services';
const FLD_AMENITIES = 'fld_amenities';
const FLD_STATUS = 'fld_status';
const FLD_COVER_IMAGE = 'fld_cover_image';
const FLD_LATITUDE = 'fld_latitude';
const FLD_LONGITUDE = 'fld_longitude';
const FLD_ADDRESS = 'fld_address';
const FLD_CITY = 'fld_city';
const FLD_STATE = 'fld_state';
const FLD_PRICE_MIN = 'fld_price_min';
const FLD_PRICE_MAX = 'fld_price_max';
const FLD_CAPACITY_MIN = 'fld_capacity_min';
const FLD_CAPACITY_MAX = 'fld_capacity_max';
const FLD_EXCERPT = 'fld_excerpt';
const FLD_DESCRIPTION = 'fld_description';
const FLD_NAME = 'fld_name';
const FLD_VERIFIED = 'fld_verified';
const FLD_FEATURED = 'fld_featured';
const FLD_PLAN = 'fld_plan';
const FLD_WHATSAPP = 'fld_whatsapp';
const FLD_PHONE = 'fld_phone';
const FLD_EMAIL = 'fld_email';
const FLD_WEBSITE = 'fld_website';
const FLD_FACEBOOK = 'fld_facebook';
const FLD_INSTAGRAM = 'fld_instagram';
const FLD_GALLERY = 'fld_gallery';
const FLD_VERIFICATION_STATUS = 'fld_verification_status';

/**
 * Page paths
 */
const PAGE_LOCATIONS = '/locations/';
const PAGE_CATEGORIES = '/categories/';

/**
 * Status values
 */
const STATUS_ACTIVE = 'active';
const STATUS_INACTIVE = 'inactive';
const STATUS_PENDING = 'pending';

/**
 * Plan values
 */
const PLAN_FREE = 'free';
const PLAN_BASIC = 'basic';
const PLAN_PREMIUM = 'premium';

/**
 * Verification status values
 */
const VERIFY_UNVERIFIED = 'unverified';
const VERIFY_BASIC = 'basic';
const VERIFY_DOCUMENTS = 'documents';
const VERIFY_ONSITE = 'onsite';
const VERIFY_REPORTED = 'reported';
