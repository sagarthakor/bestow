<?php
return [
    'Roles & Permissions' => [
        'Roles' => [
            'Create' => 'role_create',
            'Update' => 'role_update',
            'View' => 'role_view',
            'Delete' => 'role_delete'
        ],
        'User' => [
            'Create' => 'user_create',
            'Update' => 'user_update',
            'View' => 'user_view',
            'Delete' => 'user_delete'
        ]
    ],
    'Organization' => [
      'Customer' => [
          'Create' => 'customer_create',
          'Update' => 'customer_update',
          'View' => 'customer_view',
          'Delete' => 'customer_delete'
      ],
      'Customer Contact' => [
          'Create' => 'customer_contact_create',
          'Update' => 'customer_contact_update',
          'View' => 'customer_contact_view',
          'Delete' => 'customer_contact_delete'
      ],
        'Vendor' => [
            'Create' => 'vendor_create',
            'Update' => 'vendor_update',
            'View' => 'vendor_view',
            'Delete' => 'vendor_delete'
        ],
        'Vendor Contact' => [
            'Create' => 'vendor_contact_create',
            'Update' => 'vendor_contact_update',
            'View' => 'vendor_contact_view',
            'Delete' => 'vendor_contact_delete'
        ],
    ],
    'Inventory' => [

        'Product' => [
            'Create' => 'product_create',
            'Update' => 'product_update',
            'View' => 'product_view',
            'Delete' => 'product_delete'
        ],
        'Brand' => [
            'Create' => 'brand_create',
            'Update' => 'brand_update',
            'View' => 'brand_view',
            'Delete' => 'brand_delete'
        ],
        'Service' => [
            'Create' => 'service_create',
            'Update' => 'service_update',
            'View' => 'service_view',
            'Delete' => 'service_delete'
        ],
        'Service Renewal' => [
            'Create' => 'service_renewal_create',
            'Update' => 'service_renewal_update',
            'View' => 'service_renewal_view',
            'Delete' => 'service_renewal_delete'
        ]
    ],
    'Sales & Purchase' =>[
        'Quotation' => [
            'Create' => 'quotation_create',
            'Update' => 'quotation_update',
            'View' => 'quotation_view',
            'Delete' => 'quotation_delete',
            'Print' => 'quotation_print',
        ],
        'Sales' => [
            'Create' => 'sales_create',
            'Update' => 'sales_update',
            'View' => 'sales_view',
            'Delete' => 'sales_delete',
            'Print' => 'sales_print',
        ],
        'Purchase' => [
            'Create' => 'purchase_create',
            'Update' => 'purchase_update',
            'View' => 'purchase_view',
            'Delete' => 'purchase_delete',
            'Print' => 'purchase_print',
        ],
        'Delivery Challan' => [
            'Create' => 'delivery_challan_create',
            'Update' => 'delivery_challan_update',
            'View' => 'delivery_challan_view',
            'Delete' => 'delivery_challan_delete',
            'Print' => 'delivery_challan_print',
        ],
        'Invoice' => [
            'Create' => 'invoice_create',
            'Update' => 'invoice_update',
            'View' => 'invoice_view',
            'Delete' => 'invoice_delete',
            'Print' => 'invoice_print',
        ],
    ],
    'Production' =>[
        'Production Master' => [
            'Create' => 'production_master_create',
            'Update' => 'production_master_update',
            'View' => 'production_master_view',
            'Delete' => 'production_master_delete'
        ],
        'Production' => [
            'Create' => 'production_create',
            'Update' => 'production_update',
            'View' => 'production_view',
            'Delete' => 'production_delete',
        ],
        'Stitching' => [
            'Create' => 'stitching_create',
            'View' => 'stitching_view',
        ],
        'Pressing' => [
            'Create' => 'pressing_create',
            'View' => 'pressing_view',
        ],
        'Washing' => [
            'Create' => 'washing_create',
            'View' => 'washing_view',
        ],
        'Packaging' => [
            'Create' => 'packaging_create',
            'View' => 'packaging_view',
        ],
        'Formula Master' => [
            'Create' => 'formula_create',
            'Update' => 'formula_update',
            'View' => 'formula_view',
            'Delete' => 'formula_delete',

        ],
        'Buckle Formula Master' => [
            'Create' => 'buckle_formula_create',
            'Update' => 'buckle_formula_update',
            'View' => 'buckle_formula_view',
            'Delete' => 'buckle_formula_delete',
        ],
        'Belt Production' => [
            'View' => 'belt_production_view',
        ],
    ],

    'Reports' =>[
        'Quotation Report' => [
            'View' => 'quot_report_view',
        ],
        'Sales Report' => [
            'View' => 'sales_report_view',
        ],
        'Invoice Report' => [
            'View' => 'invoice_report_view',
        ],
        'Sales Report - Daily/Monthly' => [
            'View' => 'sales_summary_report_view',
        ],
        'Product Wise Sales Report' => [
            'View' => 'product_wise_sales_report_view',
        ],
        'Sales-MAN Wise Sales Report' => [
            'View' => 'salesman_wise_sales_report_view',
        ],
        'Product Wise Available Stock Report' => [
            'View' => 'stock_available_report_view',
        ],
        'RAW Material Required Pending Report' => [
            'View' => 'raw_material_pending_report_view',
        ],
        'Production Pending Report' => [
            'View' => 'production_pending_report_view',
        ],
        'Stitching Pending Report' => [
            'View' => 'stitching_pending_report_view',
        ],
        'Press Pending Report' => [
            'View' => 'pressing_pending_report_view',
        ],
        'Packing Pending Report' => [
            'View' => 'packaging_pending_report_view',
        ],
        'Belt Production Report' => [
            'View' => 'belt_production_report_view',
        ],
        'Socks Products Without Formula Report' => [
            'View' => 'socks_missing_formula_report_view',
        ],
        'Belt Products Without Formula Report' => [
            'View' => 'belt_missing_formula_report_view',
        ],
    ],

    'Machine Master' =>[
        'Machine Master' => [
            'Create' => 'machine_create',
            'Update' => 'machine_update',
            'View' => 'machine_view',
            'Delete' => 'machine_delete'
        ],
    ],
    'Others' =>[
        'Term & Conditions' => [
            'Create' => 'term_create',
            'Update' => 'term_update',
            'View' => 'term_view',
            'Delete' => 'term_delete',
        ],
        'GST' => [
            'Create' => 'gst_create',
            'Update' => 'gst_update',
            'View' => 'gst_view',
            'Delete' => 'gst_delete',
        ],
        'Payment Terms' => [
            'Create' => 'payment_terms_create',
            'Update' => 'payment_terms_update',
            'View' => 'payment_terms_view',
            'Delete' => 'payment_terms_delete',
        ],
        'Industry' => [
            'Create' => 'industry_create',
            'Update' => 'industry_update',
            'View' => 'industry_view',
            'Delete' => 'industry_delete',
        ],
        'Customer Type' => [
            'Create' => 'customer_type_create',
            'Update' => 'customer_type_update',
            'View' => 'customer_type_view',
            'Delete' => 'customer_type_delete',
        ],
        'Country' => [
            'Create' => 'country_create',
            'Update' => 'country_update',
            'View' => 'country_view',
            'Delete' => 'country_delete',
        ],
        'State' => [
            'Create' => 'state_create',
            'Update' => 'state_update',
            'View' => 'state_view',
            'Delete' => 'state_delete',
        ],
        'City' => [
            'Create' => 'city_create',
            'Update' => 'city_update',
            'View' => 'city_view',
            'Delete' => 'city_delete',
        ],
    ],
    'Company Details' => [
        'Company' => [
            'Create' => 'company_create',
            'Update' => 'company_update',
            'View' => 'company_view',
            'Delete' => 'company_delete'
        ]
    ]
];
