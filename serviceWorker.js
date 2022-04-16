const cacheName = "restorecord-v1";
const assets = [
    "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/webfonts/fa-solid-900.woff2",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/webfonts/fa-brands-400.woff2",
    "https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css",
    "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css",
    "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js",
    "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css",
    "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js",
    "https://cdn.jsdelivr.net/gh/alpinejs/alpine/dist/alpine.min.js",
    "https://cdn.restorecord.com/dashboard/assets/libs/jquery/dist/jquery.min.js",
    "https://cdn.restorecord.com/dashboard/assets/extra-libs/datatables.net-bs4/css/dataTables.bootstrap4.css",
    "https://cdn.restorecord.com/dashboard/assets/libs/chartist/dist/chartist.min.css",
    "https://cdn.restorecord.com/dashboard/assets/extra-libs/c3/c3.min.css",
    "https://cdn.restorecord.com/dashboard/dist/css/style.min.css",
    "https://cdn.restorecord.com/dashboard/unixtolocal.js",
    "https://cdn.restorecord.com/dashboard/assets/libs/popper-js/dist/umd/popper.min.js",
    "https://cdn.restorecord.com/dashboard/assets/libs/bootstrap/dist/js/bootstrap.min.js",
    "https://cdn.restorecord.com/dashboard/dist/js/app.min.js",
    "https://cdn.restorecord.com/dashboard/dist/js/app.init.dark.js",
    "https://cdn.restorecord.com/dashboard/dist/js/app-style-switcher.js",
    "https://cdn.restorecord.com/dashboard/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js",
    "https://cdn.restorecord.com/dashboard/assets/extra-libs/sparkline/sparkline.js",
    "https://cdn.restorecord.com/dashboard/dist/js/waves.js",
    "https://cdn.restorecord.com/dashboard/dist/js/sidebarmenu.js",
    "https://cdn.restorecord.com/dashboard/dist/js/feather.min.js",
    "https://cdn.restorecord.com/dashboard/dist/js/custom.min.js",
    "https://cdn.restorecord.com/dashboard/assets/libs/chartist/dist/chartist.min.js",
    "https://cdn.restorecord.com/dashboard/assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js",
    "https://cdn.restorecord.com/dashboard/dist/js/pages/dashboards/dashboard1.js",
    "https://cdn.restorecord.com/dashboard/assets/extra-libs/datatables.net/js/jquery.dataTables.min.js",
    "https://cdn.restorecord.com/dashboard/dist/js/pages/datatable/datatable-advanced.init.js",
    "https://cdn.restorecord.com/dashboard/dist/css/icons/font-awesome/webfonts/fa-brands-400.woff",
    "https://cdn.restorecord.com/dashboard/dist/css/icons/font-awesome/webfonts/fa-solid-900.woff",
    "https://cdn.restorecord.com/dashboard/dist/css/icons/material-design-iconic-font/fonts/materialdesignicons-webfont.woff",
    "https://cdn.restorecord.com/app-assets/vendors/css/vendors.min.css",
    "https://cdn.restorecord.com/app-assets/css/bootstrap.css",
    "https://cdn.restorecord.com/app-assets/css/bootstrap-extended.css",
    "https://cdn.restorecord.com/app-assets/css/colors.css",
    "https://cdn.restorecord.com/app-assets/css/components.css",
    "https://cdn.restorecord.com/app-assets/css/themes/dark-layout.css",
    "https://cdn.restorecord.com/app-assets/css/themes/bordered-layout.css",
    "https://cdn.restorecord.com/app-assets/css/themes/semi-dark-layout.css",
    "https://cdn.restorecord.com/app-assets/css/core/menu/menu-types/vertical-menu.css",
    "https://cdn.restorecord.com/app-assets/css/plugins/forms/form-validation.css",
    "https://cdn.restorecord.com/app-assets/css/pages/authentication.css",
    "https://cdn.restorecord.com/app-assets/vendors/js/vendors.min.js",
    "https://cdn.restorecord.com/app-assets/vendors/js/forms/validation/jquery.validate.min.js",
    "https://cdn.restorecord.com/app-assets/js/core/app-menu.js",
    "https://cdn.restorecord.com/app-assets/js/core/app.js",
    "https://cdn.restorecord.com/app-assets/js/scripts/pages/auth-login.js",
    "https://cdn.restorecord.com/app-assets/images/ico/favicon.ico",
]

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(cacheName).then(function(cache) {
            return cache.addAll(assets);
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request);
        })
    );
});