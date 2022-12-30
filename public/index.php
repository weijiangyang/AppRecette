<?php

use App\Kernel;


require_once dirname(__DIR__) . '/vendor/autoload_runtime.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="tarteaucitron/tarteaucitron.js"></script>
    <script>
        tarteaucitron.init({
            "privacyUrl": "",
            /* Privacy policy url */
            "bodyPosition": "bottom",
            /* or top to bring it as first element for accessibility */

            "hashtag": "#tarteaucitron",
            /* Open the panel with this hashtag */
            "cookieName": "tarteaucitron",
            /* Cookie name */

            "orientation": "middle",
            /* Banner position (top - bottom - middle - popup) */

            "groupServices": false,
            /* Group services by category */
            "serviceDefaultState": "wait",
            /* Default state (true - wait - false) */

            "showAlertSmall": true,
            /* Show the small banner on bottom right */
            "cookieslist": false,
            /* Show the cookie list */

            "showIcon": true,
            /* Show cookie icon to manage cookies */
            // "iconSrc": "", /* Optionnal: URL or base64 encoded image */
            "iconPosition": "BottomLeft",
            /* Position of the icon between BottomRight, BottomLeft, TopRight and TopLeft */

            "adblocker": false,
            /* Show a Warning if an adblocker is detected */

            "DenyAllCta": true,
            /* Show the deny all button */
            "AcceptAllCta": true,
            /* Show the accept all button when highPrivacy on */
            "highPrivacy": true,
            /* HIGHLY RECOMMANDED Disable auto consent */

            "handleBrowserDNTRequest": false,
            /* If Do Not Track == 1, disallow all */

            "removeCredit": false,
            /* Remove credit link */
            "moreInfoLink": true,
            /* Show more info link */
            "useExternalCss": false,
            /* If false, the tarteaucitron.css file will be loaded */
            "useExternalJs": false,
            /* If false, the tarteaucitron.services.js file will be loaded */

            //"cookieDomain": ".my-multisite-domaine.fr", /* Shared cookie for subdomain website */

            "readmoreLink": "",
            /* Change the default readmore link pointing to tarteaucitron.io */

            "mandatory": true,
            /* Show a message about mandatory cookies */
            "mandatoryCta": true /* Show the disabled accept button when mandatory on */
        });

        tarteaucitron.user.gtagUa = 'G-RFZWBNPBF0';
        tarteaucitron.user.gtagMore = function() {
            /* add here your optionnal gtag() */ };
        (tarteaucitron.job = tarteaucitron.job || []).push('gtag');
    </script>
</head>

<body>

</body>

</html>
<?php
return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
