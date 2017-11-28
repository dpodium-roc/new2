<?php
namespace pipwave\CustomPayment\Helper;

class UrlData {
    //url to fire api
    const URL = 'https://api.pipwave.com/payment';
    const URL_TEST = 'https://staging-api.pipwave.com/payment';

    //url to render sdk
    const RENDER_URL = 'https://secure.pipwave.com/sdk/';
    const RENDER_URL_TEST = 'https://staging-checkout.pipwave.com/sdk/';

    //url for loading image
    const LOADING_IMAGE_URL = 'https://secure.pipwave.com/images/loading.gif';
    const LOADING_IMAGE_URL_TEST = 'https://staging-checkout.pipwave.com/images/loading.gif';

    //url for controller
    const SUCCESS_URL = 'checkout/onepage/success';
    const FAIL_URL = 'checkout/onepage/failure';
    const NOTIFICATION_URL = 'notification/notification/index';
}