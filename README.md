# WooCommerce free shipping progress bar

A simple and effective progress bar that shows customers how close they are to getting free shipping. Updates in real-time as customers add or remove items from their cart.

![free-shipping-woocommerce-bar.jpg](img%2Ffree-shipping-woocommerce-bar.jpg)

### Features

* Real-time progress bar updates without page refresh
* Shows remaining amount needed for free shipping
* Works with WooCommerce shipping zones

## Installation

* Copy the code from functions.php to your theme's functions.php file
* Configure your shipping zones in WooCommerce
* Set up a free shipping method with minimum order amount

![free-shipping-woocommerce-bar-admin.jpg](img%2Ffree-shipping-woocommerce-bar-admin.jpg)

## Styles
* Add the CSS to your theme's stylesheet

```css
.free-shipping-bar {
    margin: 0 0 24px 0;
    padding: 24px;
    background-color: #fff;
    border: 1px solid #dcdcdc;
    border-radius: 6px;
    color: #848484;
}

@media (min-width: 768px) {
    .free-shipping-bar {
        width: 500px;
        float: right;
    }
}

.free-shipping-bar p {
    margin: 0 0 10px 0;
}

.free-shipping-bar .progress-bar-container {
    margin-top: 10px;
    background-color: #fff;
    border: 1px solid #dfdfdf;
    border-radius: 20px;
    height: 20px;
    overflow: hidden;
}

.free-shipping-bar .progress-bar {
    background-color: #f80;
    height: 100%;
}

.free-shipping-bar span.svgIcon {
    float: left;
    margin: 0 15px 0 0;
    top: -1px;
    position: relative;
}

.free-shipping-bar strong {
    color: #323232;
}
```

## Configuration

Change these values in the code to match your needs:

```
// Change country code (default: 'PL' for Poland)
if ('PL' === $location->code)

// Change currency format
number_format($remaining, 2, ',', '')

// Change messages
__('Add %s more for free shipping', 'woocommerce')
```

## Usage

Once installed, the progress bar will automatically appear on your cart page. It will:

1. Show current progress toward free shipping
2. Update automatically when cart changes
3. Display a success message when free shipping is achieved

Read full article about this repository here: https://ecommerce.createit.com/news/simple-free-shipping-bar-for-woocommerce-source-code/ 
