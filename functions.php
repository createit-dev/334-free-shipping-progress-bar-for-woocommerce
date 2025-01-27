<?php

/**
 * Free shipping bar indicator (WooCommerce cart view)
 */

// hide shipping calculator (cart view)

add_filter( 'woocommerce_cart_needs_shipping', 'filter_cart_needs_shipping' );
function filter_cart_needs_shipping( $needs_shipping ) {
    if ( is_cart() ) {
        $needs_shipping = false;
    }
    return $needs_shipping;
}


add_filter('woocommerce_add_to_cart_fragments', 'add_free_shipping_bar_to_fragments');

function add_free_shipping_bar_to_fragments($fragments) {
    ob_start();
    display_free_shipping_progress_bar(); // Render the progress bar HTML
    $fragments['.free-shipping-bar-container'] = ob_get_clean(); // Target the container class
    return $fragments;
}

add_action('woocommerce_before_cart_collaterals', 'display_free_shipping_progress_bar');

function display_free_shipping_progress_bar() {
    // Get all zones
    $zones = WC_Shipping_Zones::get_zones();

    // Find the zone for Poland (PL)
    $poland_zone = null;
    foreach ($zones as $zone_data) {
        foreach ($zone_data['zone_locations'] as $location) {
            if ('PL' === $location->code && 'country' === $location->type) {
                $poland_zone = $zone_data;
                break 2; // Exit both loops when found
            }
        }
    }

    if (!$poland_zone) {
        return; // Exit if no zone for PL
    }

    // Find the free shipping method in the zone
    $free_shipping_method = null;
    foreach ($poland_zone['shipping_methods'] as $method) {
        if ('free_shipping' === $method->id) {
            $free_shipping_method = $method;
            break;
        }
    }

    if (!$free_shipping_method) {
        return; // Exit if free shipping method is not available
    }

    // Get the minimum amount for free shipping
    $min_amount = isset($free_shipping_method->instance_settings['min_amount']) ? $free_shipping_method->instance_settings['min_amount'] : 0;

    if ($min_amount <= 0) {
        return; // Exit if no minimum amount is set
    }

    // Get cart total
    $cart_total = WC()->cart->get_displayed_subtotal();
    $ignore_discounts = $free_shipping_method->settings['ignore_discounts'] ?? 'no';

    if ($ignore_discounts === 'no') {
        $cart_total -= WC()->cart->get_discount_total();
        if (WC()->cart->display_prices_including_tax()) {
            $cart_total -= WC()->cart->get_discount_tax();
        }
    }

    // Calculate progress and remaining amount
    if ($cart_total >= $min_amount) {
        $progress = 100; // Fully filled
        $message = __('Gratulacje! Masz darmową dostawę!', 'woocommerce');
    } else {
        $remaining = $min_amount - $cart_total;
        $progress = ($cart_total / $min_amount) * 100; // Calculate progress percentage
        $message = sprintf(
        /* translators: %s: amount remaining for free shipping */
            __('Do darmowej dostawy brakuje ci %s', 'woocommerce'),
            '<strong>' . number_format($remaining, 2, ',', '') . ' zł</strong>'
        );
    }

    $svg_icon = file_get_contents(get_template_directory() . '/build/assets/svg/car_delivery.svg');

    // Display the progress bar and message
    echo '<div class="free-shipping-bar-container">';
    echo '<div class="free-shipping-bar">';
    echo '<p><span class="svgIcon">' . $svg_icon . '</span>' . wp_kses_post($message) . '</p>';
    echo '<div class="progress-bar-container">';
    echo '<div class="progress-bar" style="width: ' . esc_attr($progress) . '%;"></div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="clearfix"></div>';
    echo '</div>';
}


