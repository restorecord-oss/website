<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Privacy</title>

    <link rel="manifest" href="/manifest.json"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-192x192.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-256x256.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-384x384.png"/>
    <link rel="apple-touch-icon" href="https://cdn.restorecord.com/static/images/icon-512x512.png"/>


    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar" content="#4338ca"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="#4338ca">
    <meta name="apple-mobile-web-app-title" content="RestoreCord">
    <meta name="msapplication-TileImage" content="https://i.imgur.com/Nfy4OoG.png">
    <meta name="msapplication-TileColor" content="#4338ca">
    <meta name="theme-color" content="#4338ca"/>
    <meta property="og:title" content="RestoreCord"/>
    <meta property="og:description" content="RestoreCord is a verified Discord bot designed to backup your Discord Server members, roles, channels, roles & emojis"/>
    <meta property="og:url" content="https://restorecord.com"/>
    <meta property="og:image" content="https://i.imgur.com/Nfy4OoG.png"/>
    <link rel="icon" type="image/png" sizes="676x676" href="https://i.imgur.com/Nfy4OoG.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css"
          integrity="sha512-wnea99uKIC3TJF7v4eKk4Y+lMz2Mklv18+r4na2Gn1abDRPPOeef95xTzdwGD9e6zXJBteMIhZ1+68QC5byJZw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        'sm': '640px',
                        'md': '768px',
                        'lg': '1024px',
                        'xl': '1280px',

                        'sxl': '345px',
                        'sx': '425px',
                        'smx': '515px',
                        'mdx': '640px',
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine/dist/alpine.min.js" defer></script>
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="antialiased bg-slate-900" id="home">
<header class="header sticky top-0 z-10 flex w-full items-center justify-between py-5 border-b border-slate-800 backdrop-filter backdrop-blur-lg bg-opacity-30 transition-all">
    <div class="logo mx-12 xl:mx-32 hidden md:block">
        <h2 class="font-bold text-xl text-gray-200">Restore<span class="text-indigo-600">Cord</span></h2>
    </div>

    <div class="md:hidden mx-8">
        <div class="flow-root">
            <div class="logo-nav mb-4 md:mb-0 float-left">
                <h2 class="font-bold text-xl md:hidden text-gray-200">Restore<span class="text-indigo-600">Cord</span></h2>
            </div>
            <div>
                <a href="/dashboard"
                   class="float-right ml-1 bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 hover:text-gray-100 transition-all hidden sxl:block">
                    Dashboard
                </a>
                <a href="/register"
                   class="float-right mr-1 bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 hover:text-gray-100 transition-all">
                    Signup
                </a>
            </div>
        </div>


        <div x-show="showMenu">
            <nav class="navbar mb-0 flex flex-col">
                <ul class="flex gap-1 space-x-0 sxl:space-x-6 sx:space-x-12 smx:space-x-20 mdx:space-x-32">
                    <li class="font-semibold text-slate-200 hover:text-slate-100">
                        <a href="/#home">Home</a>
                    </li>
                    <li class="font-semibold text-slate-200 hover:text-slate-100">
                        <a href="/#features">Features</a>
                    </li>
                    <li class="font-semibold text-slate-200 hover:text-slate-100">
                        <a href="/#pricing">Pricing</a>
                    </li>
                    <li class="font-semibold text-slate-200 hover:text-slate-100">
                        <a href="/#stats">Statistics</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <nav class="navbar hidden md:block" id="navbar">
        <div class="logo-nav mb-4 md:mb-0">
            <h2 class="font-bold text-xl md:hidden text-gray-200">Restore<span class="text-indigo-600">Cord</span></h2>
        </div>

        <ul class="mb-6 md:mb-0 md:flex">
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold text-slate-200 hover:text-slate-100">
                <a href="/#home">Home</a>
            </li>
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold text-slate-200 hover:text-slate-100">
                <a href="/#features">Features</a>
            </li>
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold text-slate-200 hover:text-slate-100">
                <a href="/#pricing">Pricing</a>
            </li>
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold text-slate-200 hover:text-slate-100">
                <a href="/#stats">Statistics</a>
            </li>
        </ul>
    </nav>

    <ul class="hidden md:block md:flex md:items-center text-gray-200 mx-12 xl:mx-32">
        <a href="/dashboard">
            <li class="bg-indigo-600 text-white p-2 rounded-b-lg md:rounded-lg md:ml-2 hover:bg-indigo-700 hover:text-gray-100 transition-all">
                Dashboard
            </li>
        </a>
        <a href="/register">
            <li class="bg-indigo-600 text-white p-2 rounded-b-lg md:rounded-lg md:ml-2 hover:bg-indigo-700 hover:text-gray-100 transition-all">
                Signup
            </li>
        </a>
    </ul>

</header>

<section class="py-20 px-10">
    <h1 class="font-bold text-5xl mb-5 text-center lg:text-8xl text-gray-200">Privacy Policy</h1>
    <div class="items-left justify-start text-left md:pl-24 md:pr-24 lg:pl-32 lg:pr-32">

        <p class="lg:text-2xl text-gray-100">0. POSSIBLE DATA SHARED WITH SERVER OWNER</p>
        <p class="lg:text-lg text-gray-300">discord username, avatar & id</p>
        <p class="lg:text-lg text-gray-300">ip address</p>
        <p class="lg:text-lg text-gray-300">internet provider (isp)</p>
        <p class="lg:text-lg text-gray-300">country</p>
        <br>

        <p class="lg:text-2xl text-gray-100">1. WHAT INFORMATION DO WE COLLECT?</p>
        <p class="lg:text-lg text-gray-300">Personal information you disclose to us
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">In Short: We collect personal information that you
                                                               provide to us.
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">We collect personal information that you voluntarily
                                                               provide to us when you register on the Services, express an interest in obtaining information about us or
                                                               our products and Services, when you participate in activities on the Services, or otherwise when you contact
                                                               us.
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">Personal Information Provided by You. The personal
                                                               information that we collect depends on the context of your interactions with us and the Services, the
                                                               choices you make, and the products and features you use. The personal information we collect may include the
                                                               following:
        <p>
        <p class="lg:text-lg text-gray-300">email addresses</p>
        <p class="lg:text-lg text-gray-300">usernames</p>
        <p class="lg:text-lg text-gray-300">passwords (hashed)</p>
        <p class="lg:text-lg text-gray-300">ip address</p>
        <p class="lg:text-lg text-gray-300">discord oauth2 tokens (not account)</p>
        <p class="lg:text-lg text-gray-300">discord oauth2 information (e.g. username, avatar,
                                                               banner)
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">Payment Data. We may collect data necessary to process
                                                               your payment if you make purchases, such as your payment instrument number (such as a credit card number),
                                                               and the security code associated with your payment instrument. All payment data is stored by Sellix.io. You
                                                               may find their privacy notice link(s) here:
            <a class="text-white border-b border-sky-400 font-semibold hover:border-b-2 transition-all" href="https://help.sellix.io/en/articles/4534109-privacy-and-cookie-policy">https://help.sellix.io/en/articles/4534109-privacy-and-cookie-policy</a>
                                                               .
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">All personal information that you provide to us must be
                                                               true, complete, and accurate, and you must notify us of any changes to such personal information.
        <p>
            <br>
        <p class="lg:text-2xl text-gray-100">2. HOW DO WE PROCESS YOUR INFORMATION?</p>
        <p class="lg:text-lg text-gray-300">In Short: We process your information to provide,
                                                               improve, and administer our Services, communicate with you, for security and fraud prevention, and to comply
                                                               with law. We may also process your information for other purposes with your consent.
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">We process your personal information for a variety of
                                                               reasons, depending on how you interact with our Services, including:
        <p>
        <p class="lg:text-lg text-gray-300">To facilitate account creation and authentication and
                                                               otherwise manage user accounts. We may process your information so you can create and log in to your
                                                               account, as well as keep your account in working order.
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">To save or protect an individual's vital interest. We may
                                                               process your information when necessary to save or protect an individual’s vital interest, such as to
                                                               prevent harm.
        <p>
            <br>
        <p class="lg:text-2xl text-gray-100">3. WHEN AND WITH WHOM DO WE SHARE YOUR PERSONAL
                                                                INFORMATION?
        </p>
        <p class="lg:text-lg text-gray-300">In Short: We may share information in specific situations
                                                               described in this section and/or with the following third parties.
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">We may need to share your personal information in the
                                                               following situations:
        <p>
        <p class="lg:text-lg text-gray-300">Business Transfers. We may share or transfer your
                                                               information in connection with, or during negotiations of, any merger, sale of company assets, financing, or
                                                               acquisition of all or a portion of our business to another company.
        <p>
            <br>
        <p class="lg:text-2xl text-gray-100">4. HOW LONG DO WE KEEP YOUR INFORMATION?</p>
        <p class="lg:text-lg text-gray-300">In Short: We keep your information for as long as
                                                               necessary to fulfill the purposes outlined in this privacy notice unless otherwise required by law.
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">We will only keep your personal information for as long
                                                               as it is necessary for the purposes set out in this privacy notice, unless a longer retention period is
                                                               required or permitted by law (such as tax, accounting, or other legal requirements). No purpose in this
                                                               notice will require us keeping your personal information for longer than the period of time in which users
                                                               have an account with us.
        <p>
            <br>
        <p class="lg:text-lg text-gray-300">When we have no ongoing legitimate business need to
                                                               process your personal information, we will either delete or anonymize such information, or, if this is not
                                                               possible (for example, because your personal information has been stored in backup archives), then we will
                                                               securely store your personal information and isolate it from any further processing until deletion is
                                                               possible.
        <p>
            <br>
        <p class="lg:text-2xl text-gray-100">5. HOW DO WE KEEP YOUR INFORMATION SAFE?</p>
        <p class="lg:text-lg text-gray-300">In Short: We aim to protect your personal information
                                                               through a system of organizational and technical security measures.
        <p>
        <p class="lg:text-lg text-gray-300">We have implemented appropriate and reasonable technical
                                                               and organizational security measures designed to protect the security of any personal information we
                                                               process. However, despite our safeguards and efforts to secure your information, no electronic transmission
                                                               over the Internet or information storage technology can be guaranteed to be 100% secure, so we cannot
                                                               promise or guarantee that hackers, cybercriminals, or other unauthorized third parties will not be able to
                                                               defeat our security and improperly collect, access, steal, or modify your information. Although we will do
                                                               our best to protect your personal information, transmission of personal information to and from our Services
                                                               is at your own risk. You should only access the Services within a secure environment.
        </p>
        <br>
        <p class="lg:text-2xl text-gray-100">6. DO WE MAKE UPDATES TO THIS NOTICE?</p>
        <p class="lg:text-lg text-gray-300">In Short: Yes, we will update this notice as necessary to
                                                               stay compliant with relevant laws.
        <p>
        <p class="lg:text-lg text-gray-300">We may update this privacy notice from time to time. The
                                                               updated version will be indicated by an updated "Revised" date and the updated version will be effective as
                                                               soon as it is accessible. If we make material changes to this privacy notice, we may notify you either by
                                                               prominently posting a notice of such changes or by directly sending you a notification. We encourage you to
                                                               review this privacy notice frequently to be informed of how we are protecting your information.
        <p>
            <br>
        <p class="lg:text-2xl text-gray-100">7. HOW CAN YOU CONTACT US ABOUT THIS NOTICE?</p>
        <p class="lg:text-lg text-gray-300">If you have questions or comments about this notice, you
                                                               may email us at support@restorecord.com.
        <p>
            <br>
        <p class="lg:text-2xl text-gray-100">8. HOW CAN YOU REVIEW, UPDATE, OR DELETE THE DATA WE
                                                                COLLECT FROM YOU?
        </p>
        <p class="lg:text-lg text-gray-300">Based on the applicable laws of your country, you may
                                                               have the right to request access to the personal information we collect from you, change that information,
                                                               or delete it in some circumstances. To request to review, update, or delete your personal information,
                                                               please submit a request form by clicking here.
        <p>
    </div>
</section>

<footer class="pb-10">
    <ul class="flex items-center justify-center">
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-200"
               href="https://discord.gg/restore">Support
            </a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-200" href="#pricing">Pricing</a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-200"
               href="mailto:support@restorecord.com">Contact
            </a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-200"
               href="/terms">Terms
            </a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-200" href="/privacy">Privacy</a>
        </li>
    </ul>

    <ul class="flex items-center justify-center my-5">
        <li class="mx-1">
            <a href="https://discord.gg/restore">
                <i class="text-xl hover:text-gray-400 text-gray-200 transition-all cursor-pointer fab fa-discord"></i>
            </a>
        </li>
        <li class="mx-1">
            <a href="https://www.youtube.com/channel/UCdO4LjbTjSJWxP9VQg7ZNXw">
                <i class="text-xl hover:text-gray-400 text-gray-200 transition-all cursor-pointer fab fa-youtube"></i>
            </a>
        </li>
        <li class="mx-1">
            <a href="https://twitter.com/restorecord">
                <i class="text-xl hover:text-gray-400 text-gray-200 transition-all cursor-pointer fab fa-twitter"></i>
            </a>
        </li>
    </ul>

    <div class="text-center">
        <p class="text-gray-400"></p>
    </div>
</footer>

<script>
    window.addEventListener('load', function () {
        document.querySelector('footer.pb-10 .text-center p').innerText = 'Copyright © ' + new Date().getFullYear() + ' RestoreCord';
    });
</script>
</body>
</html>