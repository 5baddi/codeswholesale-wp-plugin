<?php

/**
 * PHP version 7.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */

namespace BaddiServices\CodesWholesale;

use BaddiServices\CodesWholesale\CodesWholesaleBy5baddi;

/**
 * Class Constants.
 *
 * @category PHP
 *
 * @author   5baddi <project@baddi.info>
 *
 * @link     http://baddi.info
 */
class Constants
{
    // Options
    public const API_CLIENT_ID_OPTION = 'cws5baddi_api_client_id';
    public const API_CLIENT_SECRET_OPTION = 'cws5baddi_api_client_secret';
    public const API_CLIENT_SIGNATURE_OPTION = 'cws5baddi_api_client_signature';
    public const PROFIT_MARGIN_TYPE_OPTION = 'cws5baddi_profit_margin_type';
    public const PROFIT_MARGIN_VALUE_OPTION = 'cws5baddi_profit_margin_value';
    public const CURRENCY_OPTION = 'cws5baddi_currency';
    public const AUTO_COMPLETE_ORDERS_OPTION = 'cws5baddi_auto_complete_orders';
    public const PRE_ORDER_PRODUCTS_OPTION = 'cws5baddi_pre_order_products';
    public const AUTOMATIC_PRODUCT_IMPORT_OPTION = 'cws5baddi_automatic_product_import';
    public const LOW_BALANCE_NOTIFICATION_OPTION = 'cws5baddi_low_balance_notification';
    public const RISK_SCORE_VALUE_OPTION = 'cws5baddi_risk_score_value';
    public const DOUBLE_CHECK_PRICE_OPTION = 'cws5baddi_double_check_price';
    public const HIDE_PRODUCTS_OPTION = 'cws5baddi_hide_products';
    public const PRODUCT_DESCRIPTION_LANGUAGE_OPTION = 'cws5baddi_product_description_language';
    public const CHARM_PRICING_OPTION = 'cws5baddi_charm_pricing';
    public const BEARER_TOKEN_OPTION = 'cws5baddi_bearer_token';
    public const BEARER_TOKEN_EXPIRES_IN_OPTION = 'cws5baddi_bearer_token_expires_in';
    public const SUPPORTED_PRODUCT_DESCRIPTION_LANGUAGES_OPTION = 'cws5baddi_supported_product_description_languages';
    public const SUPPORTED_REGIONS_OPTION = 'cws5baddi_supported_regions';
    public const SUPPORTED_TERRITORIES_OPTION = 'cws5baddi_supported_territories';
    public const SUPPORTED_PLATFORMS_OPTION = 'cws5baddi_supported_platforms';
    public const ACCOUNT_DETAILS_OPTION = 'cws5baddi_account_details';
    public const API_MODE_OPTION = 'cws5baddi_api_mode';
    public const ALLOWED_RISK_SCORE_OPTION = 'cws5baddi_allowed_risk_score';
    public const ALLOW_PRE_ORDER_OPTION = 'cws5baddi_allow_pre_order';

    public const PROFIT_MARGIN_AMOUNT = 1;
    public const PROFIT_MARGIN_PERCENTAGE = 2;

    public const API_SANDBOX_MODE = 'sandbox';
    public const API_LIVE_MODE = 'live';

    public const ALLOWED_ORIGINS = [
        'codeswholesale.com',
    ];

    public const PAGINATION_ITEMS_PER_PAGE = 25;

    // Default values
    public const DEFAULT_PROFIT_MARGIN_VALUE = 5;
    public const DEFAULT_PROFIT_MARGIN_TYPE = self::PROFIT_MARGIN_PERCENTAGE;
    public const DEFAULT_CURRENCY = 'EUR';
    public const DEFAULT_LOW_BALANCE_NOTIFICATION_VALUE = 100;
    public const DEFAULT_RISK_SCORE_VALUE = 2;
    public const DEFAULT_PRODUCT_DESCRIPTION_LANGUAGE = 'en';
    public const DEFAULT_GRANT_TYPE = 'client_credentials';
    public const DEFAULT_ALLOWED_RISK_SCORE = 1.5;

    public const CURRENCIES_LIST = [
        "AFA" => "Afghan Afghani",
        "ALL" => "Albanian Lek",
        "DZD" => "Algerian Dinar",
        "AOA" => "Angolan Kwanza",
        "ARS" => "Argentine Peso",
        "AMD" => "Armenian Dram",
        "AWG" => "Aruban Florin",
        "AUD" => "Australian Dollar",
        "AZN" => "Azerbaijani Manat",
        "BSD" => "Bahamian Dollar",
        "BHD" => "Bahraini Dinar",
        "BDT" => "Bangladeshi Taka",
        "BBD" => "Barbadian Dollar",
        "BYR" => "Belarusian Ruble",
        "BEF" => "Belgian Franc",
        "BZD" => "Belize Dollar",
        "BMD" => "Bermudan Dollar",
        "BTN" => "Bhutanese Ngultrum",
        "BTC" => "Bitcoin",
        "BOB" => "Bolivian Boliviano",
        "BAM" => "Bosnia",
        "BWP" => "Botswanan Pula",
        "BRL" => "Brazilian Real",
        "GBP" => "British Pound Sterling",
        "BND" => "Brunei Dollar",
        "BGN" => "Bulgarian Lev",
        "BIF" => "Burundian Franc",
        "KHR" => "Cambodian Riel",
        "CAD" => "Canadian Dollar",
        "CVE" => "Cape Verdean Escudo",
        "KYD" => "Cayman Islands Dollar",
        "XOF" => "CFA Franc BCEAO",
        "XAF" => "CFA Franc BEAC",
        "XPF" => "CFP Franc",
        "CLP" => "Chilean Peso",
        "CNY" => "Chinese Yuan",
        "COP" => "Colombian Peso",
        "KMF" => "Comorian Franc",
        "CDF" => "Congolese Franc",
        "CRC" => "Costa Rican ColÃ³n",
        "HRK" => "Croatian Kuna",
        "CUC" => "Cuban Convertible Peso",
        "CZK" => "Czech Republic Koruna",
        "DKK" => "Danish Krone",
        "DJF" => "Djiboutian Franc",
        "DOP" => "Dominican Peso",
        "XCD" => "East Caribbean Dollar",
        "EGP" => "Egyptian Pound",
        "ERN" => "Eritrean Nakfa",
        "EEK" => "Estonian Kroon",
        "ETB" => "Ethiopian Birr",
        "EUR" => "Euro",
        "FKP" => "Falkland Islands Pound",
        "FJD" => "Fijian Dollar",
        "GMD" => "Gambian Dalasi",
        "GEL" => "Georgian Lari",
        "DEM" => "German Mark",
        "GHS" => "Ghanaian Cedi",
        "GIP" => "Gibraltar Pound",
        "GRD" => "Greek Drachma",
        "GTQ" => "Guatemalan Quetzal",
        "GNF" => "Guinean Franc",
        "GYD" => "Guyanaese Dollar",
        "HTG" => "Haitian Gourde",
        "HNL" => "Honduran Lempira",
        "HKD" => "Hong Kong Dollar",
        "HUF" => "Hungarian Forint",
        "ISK" => "Icelandic KrÃ³na",
        "INR" => "Indian Rupee",
        "IDR" => "Indonesian Rupiah",
        "IRR" => "Iranian Rial",
        "IQD" => "Iraqi Dinar",
        "ILS" => "Israeli New Sheqel",
        "ITL" => "Italian Lira",
        "JMD" => "Jamaican Dollar",
        "JPY" => "Japanese Yen",
        "JOD" => "Jordanian Dinar",
        "KZT" => "Kazakhstani Tenge",
        "KES" => "Kenyan Shilling",
        "KWD" => "Kuwaiti Dinar",
        "KGS" => "Kyrgystani Som",
        "LAK" => "Laotian Kip",
        "LVL" => "Latvian Lats",
        "LBP" => "Lebanese Pound",
        "LSL" => "Lesotho Loti",
        "LRD" => "Liberian Dollar",
        "LYD" => "Libyan Dinar",
        "LTL" => "Lithuanian Litas",
        "MOP" => "Macanese Pataca",
        "MKD" => "Macedonian Denar",
        "MGA" => "Malagasy Ariary",
        "MWK" => "Malawian Kwacha",
        "MYR" => "Malaysian Ringgit",
        "MVR" => "Maldivian Rufiyaa",
        "MRO" => "Mauritanian Ouguiya",
        "MUR" => "Mauritian Rupee",
        "MXN" => "Mexican Peso",
        "MDL" => "Moldovan Leu",
        "MNT" => "Mongolian Tugrik",
        "MAD" => "Moroccan Dirham",
        "MZM" => "Mozambican Metical",
        "MMK" => "Myanmar Kyat",
        "NAD" => "Namibian Dollar",
        "NPR" => "Nepalese Rupee",
        "ANG" => "Netherlands Antillean Guilder",
        "TWD" => "New Taiwan Dollar",
        "NZD" => "New Zealand Dollar",
        "NIO" => "Nicaraguan CÃ³rdoba",
        "NGN" => "Nigerian Naira",
        "KPW" => "North Korean Won",
        "NOK" => "Norwegian Krone",
        "OMR" => "Omani Rial",
        "PKR" => "Pakistani Rupee",
        "PAB" => "Panamanian Balboa",
        "PGK" => "Papua New Guinean Kina",
        "PYG" => "Paraguayan Guarani",
        "PEN" => "Peruvian Nuevo Sol",
        "PHP" => "Philippine Peso",
        "PLN" => "Polish Zloty",
        "QAR" => "Qatari Rial",
        "RON" => "Romanian Leu",
        "RUB" => "Russian Ruble",
        "RWF" => "Rwandan Franc",
        "SVC" => "Salvadoran ColÃ³n",
        "WST" => "Samoan Tala",
        "SAR" => "Saudi Riyal",
        "RSD" => "Serbian Dinar",
        "SCR" => "Seychellois Rupee",
        "SLL" => "Sierra Leonean Leone",
        "SGD" => "Singapore Dollar",
        "SKK" => "Slovak Koruna",
        "SBD" => "Solomon Islands Dollar",
        "SOS" => "Somali Shilling",
        "ZAR" => "South African Rand",
        "KRW" => "South Korean Won",
        "XDR" => "Special Drawing Rights",
        "LKR" => "Sri Lankan Rupee",
        "SHP" => "St. Helena Pound",
        "SDG" => "Sudanese Pound",
        "SRD" => "Surinamese Dollar",
        "SZL" => "Swazi Lilangeni",
        "SEK" => "Swedish Krona",
        "CHF" => "Swiss Franc",
        "SYP" => "Syrian Pound",
        "STD" => "São Tomé and Príncipe Dobra",
        "TJS" => "Tajikistani Somoni",
        "TZS" => "Tanzanian Shilling",
        "THB" => "Thai Baht",
        "TOP" => "Tongan pa'anga",
        "TTD" => "Trinidad & Tobago Dollar",
        "TND" => "Tunisian Dinar",
        "TRY" => "Turkish Lira",
        "TMT" => "Turkmenistani Manat",
        "UGX" => "Ugandan Shilling",
        "UAH" => "Ukrainian Hryvnia",
        "AED" => "United Arab Emirates Dirham",
        "UYU" => "Uruguayan Peso",
        "USD" => "US Dollar",
        "UZS" => "Uzbekistan Som",
        "VUV" => "Vanuatu Vatu",
        "VEF" => "Venezuelan BolÃvar",
        "VND" => "Vietnamese Dong",
        "YER" => "Yemeni Rial",
        "ZMK" => "Zambian Kwacha"
    ];

    public const LANGUAGES_LIST = [
        'ab' => 'Abkhazian',
        'aa' => 'Afar',
        'af' => 'Afrikaans',
        'ak' => 'Akan',
        'sq' => 'Albanian',
        'am' => 'Amharic',
        'ar' => 'Arabic',
        'an' => 'Aragonese',
        'hy' => 'Armenian',
        'as' => 'Assamese',
        'av' => 'Avaric',
        'ae' => 'Avestan',
        'ay' => 'Aymara',
        'az' => 'Azerbaijani',
        'bm' => 'Bambara',
        'ba' => 'Bashkir',
        'eu' => 'Basque',
        'be' => 'Belarusian',
        'bn' => 'Bengali',
        'bh' => 'Bihari languages',
        'bi' => 'Bislama',
        'bs' => 'Bosnian',
        'br' => 'Breton',
        'bg' => 'Bulgarian',
        'my' => 'Burmese',
        'ca' => 'Catalan, Valencian',
        'km' => 'Central Khmer',
        'ch' => 'Chamorro',
        'ce' => 'Chechen',
        'ny' => 'Chichewa, Chewa, Nyanja',
        'zh' => 'Chinese',
        'cu' => 'Church Slavonic, Old Bulgarian, Old Church Slavonic',
        'cv' => 'Chuvash',
        'kw' => 'Cornish',
        'co' => 'Corsican',
        'cr' => 'Cree',
        'hr' => 'Croatian',
        'cs' => 'Czech',
        'da' => 'Danish',
        'dv' => 'Divehi, Dhivehi, Maldivian',
        'nl' => 'Dutch, Flemish',
        'dz' => 'Dzongkha',
        'en' => 'English',
        'eo' => 'Esperanto',
        'et' => 'Estonian',
        'ee' => 'Ewe',
        'fo' => 'Faroese',
        'fj' => 'Fijian',
        'fi' => 'Finnish',
        'fr' => 'French',
        'ff' => 'Fulah',
        'gd' => 'Gaelic, Scottish Gaelic',
        'gl' => 'Galician',
        'lg' => 'Ganda',
        'ka' => 'Georgian',
        'de' => 'German',
        'ki' => 'Gikuyu, Kikuyu',
        'el' => 'Greek (Modern)',
        'kl' => 'Greenlandic, Kalaallisut',
        'gn' => 'Guarani',
        'gu' => 'Gujarati',
        'ht' => 'Haitian, Haitian Creole',
        'ha' => 'Hausa',
        'he' => 'Hebrew',
        'hz' => 'Herero',
        'hi' => 'Hindi',
        'ho' => 'Hiri Motu',
        'hu' => 'Hungarian',
        'is' => 'Icelandic',
        'io' => 'Ido',
        'ig' => 'Igbo',
        'id' => 'Indonesian',
        'ia' => 'Interlingua (International Auxiliary Language Association)',
        'ie' => 'Interlingue',
        'iu' => 'Inuktitut',
        'ik' => 'Inupiaq',
        'ga' => 'Irish',
        'it' => 'Italian',
        'ja' => 'Japanese',
        'jv' => 'Javanese',
        'kn' => 'Kannada',
        'kr' => 'Kanuri',
        'ks' => 'Kashmiri',
        'kk' => 'Kazakh',
        'rw' => 'Kinyarwanda',
        'kv' => 'Komi',
        'kg' => 'Kongo',
        'ko' => 'Korean',
        'kj' => 'Kwanyama, Kuanyama',
        'ku' => 'Kurdish',
        'ky' => 'Kyrgyz',
        'lo' => 'Lao',
        'la' => 'Latin',
        'lv' => 'Latvian',
        'lb' => 'Letzeburgesch, Luxembourgish',
        'li' => 'Limburgish, Limburgan, Limburger',
        'ln' => 'Lingala',
        'lt' => 'Lithuanian',
        'lu' => 'Luba-Katanga',
        'mk' => 'Macedonian',
        'mg' => 'Malagasy',
        'ms' => 'Malay',
        'ml' => 'Malayalam',
        'mt' => 'Maltese',
        'gv' => 'Manx',
        'mi' => 'Maori',
        'mr' => 'Marathi',
        'mh' => 'Marshallese',
        'ro' => 'Moldovan, Moldavian, Romanian',
        'mn' => 'Mongolian',
        'na' => 'Nauru',
        'nv' => 'Navajo, Navaho',
        'nd' => 'Northern Ndebele',
        'ng' => 'Ndonga',
        'ne' => 'Nepali',
        'se' => 'Northern Sami',
        'no' => 'Norwegian',
        'nb' => 'Norwegian Bokmål',
        'nn' => 'Norwegian Nynorsk',
        'ii' => 'Nuosu, Sichuan Yi',
        'oc' => 'Occitan (post 1500)',
        'oj' => 'Ojibwa',
        'or' => 'Oriya',
        'om' => 'Oromo',
        'os' => 'Ossetian, Ossetic',
        'pi' => 'Pali',
        'pa' => 'Panjabi, Punjabi',
        'ps' => 'Pashto, Pushto',
        'fa' => 'Persian',
        'pl' => 'Polish',
        'pt' => 'Portuguese',
        'qu' => 'Quechua',
        'rm' => 'Romansh',
        'rn' => 'Rundi',
        'ru' => 'Russian',
        'sm' => 'Samoan',
        'sg' => 'Sango',
        'sa' => 'Sanskrit',
        'sc' => 'Sardinian',
        'sr' => 'Serbian',
        'sn' => 'Shona',
        'sd' => 'Sindhi',
        'si' => 'Sinhala, Sinhalese',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'so' => 'Somali',
        'st' => 'Sotho, Southern',
        'nr' => 'South Ndebele',
        'es' => 'Spanish, Castilian',
        'su' => 'Sundanese',
        'sw' => 'Swahili',
        'ss' => 'Swati',
        'sv' => 'Swedish',
        'tl' => 'Tagalog',
        'ty' => 'Tahitian',
        'tg' => 'Tajik',
        'ta' => 'Tamil',
        'tt' => 'Tatar',
        'te' => 'Telugu',
        'th' => 'Thai',
        'bo' => 'Tibetan',
        'ti' => 'Tigrinya',
        'to' => 'Tonga (Tonga Islands)',
        'ts' => 'Tsonga',
        'tn' => 'Tswana',
        'tr' => 'Turkish',
        'tk' => 'Turkmen',
        'tw' => 'Twi',
        'ug' => 'Uighur, Uyghur',
        'uk' => 'Ukrainian',
        'ur' => 'Urdu',
        'uz' => 'Uzbek',
        've' => 'Venda',
        'vi' => 'Vietnamese',
        'vo' => 'Volap_k',
        'wa' => 'Walloon',
        'cy' => 'Welsh',
        'fy' => 'Western Frisian',
        'wo' => 'Wolof',
        'xh' => 'Xhosa',
        'yi' => 'Yiddish',
        'yo' => 'Yoruba',
        'za' => 'Zhuang, Chuang',
        'zu' => 'Zulu'
    ];

    public static function sharedData(): array
    {
        return [
            'currencies'     => self::CURRENCIES_LIST,
            'languages'      => self::LANGUAGES_LIST,
            'logo'           => sprintf('%simg/logo.svg', CWS_5BADDI_PLUGIN_ASSETS_URL),
            'urls'           => [
                'accountSettings' => admin_url(sprintf('admin.php?page=%s-account-details', CodesWholesaleBy5baddi::SLUG)),
                'generalSettings' => admin_url(sprintf('admin.php?page=%s', CodesWholesaleBy5baddi::SLUG)),
                'importProducts'  => admin_url(sprintf('admin.php?page=%s-import-products', CodesWholesaleBy5baddi::SLUG)),
                'ordersHistory'   => admin_url(sprintf('admin.php?page=%s-orders-history', CodesWholesaleBy5baddi::SLUG)),
                'wooProducts'     => admin_url('edit.php?post_type=product'),
                'rest'            => get_rest_url(),
            ],
            'isDebugMode' => (defined('WP_DEBUG') && WP_DEBUG === true),
            'apiNonce'    => wp_create_nonce('wp_rest'),
            'slug'        => CodesWholesaleBy5baddi::SLUG,
            'namespace'   => CodesWholesaleBy5baddi::NAMESPACE,
        ];
    }

    public static function translations(): array
    {
        return [
            'error' => cws5baddiTranslation('Something going wrong! please try again or contact support...'),
        ];
    }
}
