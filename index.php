<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord</title>

    <link rel="manifest" href="manifest.json"/>
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
                darkMode: 'class',
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
<body class="antialiased dark:bg-slate-900 dark" id="home">
<header class="header sticky top-0 z-10 flex w-full items-center justify-between py-5 border-b border-gray-200 dark:border-slate-800 backdrop-filter backdrop-blur-lg bg-opacity-30 transition-all">
    <div class="logo mx-12 xl:mx-32 hidden md:block">
        <h2 class="text-gray-900 font-bold text-xl dark:text-gray-200">Restore<span class="text-indigo-600">Cord</span></h2>
    </div>

    <div class="md:hidden mx-8">
        <div class="flow-root">
            <div class="logo-nav mb-4 md:mb-0 float-left">
                <h2 class="text-gray-900 font-bold text-xl md:hidden dark:text-gray-200">Restore
                    <span
                            class="text-indigo-600">Cord
                    </span>
                </h2>
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
                    <li class="font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                        <a href="#home">Home</a>
                    </li>
                    <li class="font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                        <a href="#features">Features</a>
                    </li>
                    <li class="font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                        <a href="#pricing">Pricing</a>
                    </li>
                    <li class="font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                        <a href="#stats">Statistics</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <nav class="navbar hidden md:block" id="navbar">
        <div class="logo-nav mb-4 md:mb-0">
            <h2 class="text-gray-900 font-bold text-xl md:hidden dark:text-gray-200">Restore
                <span
                        class="text-indigo-600">Cord
                </span>
            </h2>
        </div>

        <ul class="mb-6 md:mb-0 md:flex">
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                <a href="#home">Home</a>
            </li>
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                <a href="#features">Features</a>
            </li>
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                <a href="#pricing">Pricing</a>
            </li>
            <li class="mb-2 border-b border-gray-200 md:border-0 md:mx-2 font-semibold dark:text-slate-200 dark:hover:text-slate-100 hover:text-gray-500">
                <a href="#stats">Statistics</a>
            </li>
        </ul>
    </nav>

    <div class="hidden md:block md:flex md:items-center text-gray-200 mx-12 xl:mx-32">
        <a href="/dashboard">
            <button class="bg-indigo-600 text-white p-2 rounded-b-lg md:rounded-lg md:ml-2 hover:bg-indigo-700 hover:text-gray-100 transition-all">
                Dashboard
            </button>
        </a>
        <a href="/register">
            <button class="bg-indigo-600 text-white p-2 rounded-b-lg md:rounded-lg md:ml-2 hover:bg-indigo-700 hover:text-gray-100 transition-all">
                Signup
            </button>
        </a>
    </div>

</header>

<section class="showcase py-20 px-10 flex flex-col items-center justify-center">
    <h1 class="text-gray-900 font-bold text-5xl mb-5 text-center lg:text-8xl dark:text-gray-200">The
        <span
                class="text-indigo-600">only
        </span>
                                                                                                 Backup Bot
    </h1>
    <p class="text-gray-600 lg:text-xl dark:text-gray-200 text-center">RestoreCord helps you Backup your Discord Server,
                                                                       you can
                                                                       save your Server
                                                                       Channels, Roles, Settings and Members.
    </p>

    <div class="relative mt-10">
        <a href="/register"
           class="bg-indigo-600 p-3 rounded-full text-white block w-44 text-center shadow-lg transition-all border-2 border-indigo-600 hover:shadow-sm cursor-pointer hover:bg-transparent hover:text-indigo-600">
            Purchase Now
        </a>
        <p class="absolute -top-3 -right-2 bg-green-500 py-1 px-2 rounded-full text-white text-xs">only $9.99/year</p>
        <a href="https://discord.gg/s6gk5Y5fTC" class="block w-44 text-indigo-600 text-center mt-4">Support Server</a>
    </div>
</section>

<section class="pt-32 pb-20 px-10" id="features">
    <h2 class="font-bold text-4xl text-center text-gray-900 dark:text-gray-200">Our Features</h2>

    <div class="cards mt-10 sm:grid sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 xl:px-32">
        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-gear"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Backup Server Settings</h4>
            <p>Our Bot will let you Backup all of your Server's Channels, Roles, User Roles and Settings.</p>
        </div>

        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-xmark" style="width: 56px; height: 56px"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Auto Kick</h4>
            <p>We will automatically kick your non verified members.</p>
        </div>

        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-pen"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Customization</h4>
            <p>You can customize every message the bot has sent, and almost every element on the Verification Page.</p>
        </div>

        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-file"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Verification Logs</h4>
            <p>Want to see when people verify? We offer Verification Logs via Discord Webhooks.</p>
        </div>

        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-network-wired"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Anti VPN/Proxy</h4>
            <p>We offer a premium level Anti VPN, so no one using a VPN or Proxy can verify.</p>
        </div>

        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-user-slash"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">IP Ban</h4>
            <p>Want to Permanently Ban someone from your Server? We have IP Bans which will help banning a person from
               your Server.
            </p>
        </div>

    </div>
</section>

<section class="bg-gray-100 dark:bg-slate-900 pb-20 md:grid md:grid-cols-2 md:items-center xl:px-32">
    <div class="pt-5 px-10">
        <h5 class="uppercase text-indigo-600 text-xl">
            What our customers say
        </h5>
        <h3 class="font-bold text-3xl text-gray-900 mt-2 mb-4 lg:text-6xl dark:text-gray-200">
            Testimonials
        </h3>
        <p class="mb-10 text-gray-600 dark:text-gray-200">
            Don't just take our word for it, read what our customers have to say about us.
        </p>
        <a href="https://www.trustpilot.com/review/restorecord.com"
           class="bg-indigo-600 p-3 rounded-lg text-white shadow-lg transition-all border-2 border-indigo-600 hover:shadow-sm cursor-pointer hover:bg-transparent hover:text-indigo-600 font-bold">
            Read More
        </a>
    </div>

    <div class="cards px-10 mt-16 grid grid-cols-1 gap-5">
        <div class="card flex justify-between pl-6 pr-6 md:pl-16 md:pr-16 bg-white p-5 rounded-lg shadow-lg transition-all hover:shadow-sm dark:bg-slate-800 dark:text-white">
            <div>
                <p class="mr-5 mb-3">+ rep mods act fast and amazing support fixed in seconds
                </p>
                <h6 class="font-bold text-gray-900 dark:text-gray-200">daniel_#7252</h6>
            </div>

            <div>
                <img class="w-20 rounded-full"
                     src="https://cdn.discordapp.com/avatars/951159000739754087/a_c6ec0f50f0fa1b93d4c2e2125ec8abe0.png?size=48"
                     alt="daniel"
                     loading="lazy" width="80" height="80"/>
            </div>
        </div>

        <div class="card flex justify-between pl-6 pr-6 md:pl-16 md:pr-16 bg-white p-5 rounded-lg shadow-lg transition-all hover:shadow-sm dark:bg-slate-800 dark:text-white">
            <div>
                <p class="mr-5 mb-3">+rep restorecord always comes in clutch
                </p>
                <h6 class="font-bold text-gray-900 dark:text-gray-200">! Recerse#1634</h6>
            </div>

            <div>
                <img class="w-20 rounded-full"
                     src="https://cdn.discordapp.com/avatars/902062159176032316/d35cdd997fac8488db7ea5a9c6d8ed48.png?size=48"
                     alt="recerse"
                     loading="lazy" width="80" height="80"/>
            </div>
        </div>

        <div class="card flex justify-between pl-6 pr-6 md:pl-16 md:pr-16 bg-white p-5 rounded-lg shadow-lg transition-all hover:shadow-sm dark:bg-slate-800 dark:text-white">
            <div>
                <p class="mr-5 mb-3">+rep devs always help out fast with issues that come up
                </p>
                <h6 class="font-bold text-gray-900 dark:text-gray-200">MaxiPad#0001</h6>
            </div>

            <div>
                <img class="w-20 rounded-full"
                     src="https://cdn.discordapp.com/avatars/463034459634008075/a_2ff73fcd42b640ed16206a56ce4d252a.png?size=48"
                     alt="maxipad"
                     loading="lazy" width="80" height="80"/>
            </div>
        </div>
    </div>
</section>

<section class="pt-20" id="pricing">
    <div class="px-10 text-center mb-5">
        <h4 class="font-bold text-3xl mb-1 text-gray-900 dark:text-gray-200">
            Our Subscription Plans
        </h4>
    </div>

    <div class="grid grid-cols-1 gap-5 px-10 sm:grid-cols-1 lg:grid-cols-3 xl:px-32">
        <div class="text-center border border-gray-400 p-5 rounded-lg hover:border-gray-300 transition-all">
            <h3 class="font-bold text-2xl mb-5 text-gray-900 dark:text-gray-200">Free</h3>

            <h5 class="text-5xl text-gray-900 dark:text-gray-200 font-bold">
                $0
                <span class="text-gray-600 dark:text-gray-200 text-base font-semibold">/year</span>
            </h5>

            <ul class="text-left my-5">
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    100 Member Capacity
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    1 Server
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Server Backups
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    IP Bans
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Anti VPN/Proxy
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Verification Logs
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Auto Kick
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Vanity Url
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    User Role Backup
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Customization
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    API Access
                </li>
            </ul>

            <a href="/register"
               class="border border-indigo-600 block w-full rounded-lg p-3 hover:bg-indigo-600 text-indigo-600 hover:text-white transition-all">
                Select Plan
            </a>
        </div>

        <div class="text-center border-4 border-indigo-600 p-5 rounded-lg hover:border-indigo-500 transition-all">
            <h3 class="font-bold text-2xl mb-5 text-gray-900 dark:text-gray-200">Premium</h3>

            <h5 class="text-5xl text-gray-900 dark:text-gray-200 font-bold">$9.99
                <span
                        class="text-gray-600 dark:text-gray-200 text-base font-semibold">/year
                </span>
            </h5>

            <ul class="text-left my-5">
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Unlimited Member Capacity
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    5 Servers
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Server Backups
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    IP Bans
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Anti VPN/Proxy
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Verification Logs
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Auto Kick
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Vanity Url
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    User Role Backup
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    Customization
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-x text-indigo-600"></i>
                    API Access
                </li>
            </ul>

            <a href="/register"
               class="bg-indigo-600 border border-indigo-600 block w-full rounded-lg p-3 hover:bg-indigo-700 text-white transition-all">
                Select Plan
            </a>
        </div>

        <div class="text-center border border-gray-400 p-5 rounded-lg hover:border-gray-300 transition-all">
            <h3 class="font-bold text-2xl mb-5 text-gray-900 dark:text-gray-200">Business</h3>

            <h5 class="text-5xl text-gray-900 dark:text-gray-200 font-bold">$29.99
                <span
                        class="text-gray-600 dark:text-gray-200 text-base font-semibold">/year
                </span>
            </h5>

            <ul class="text-left my-5">
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Unlimited Member Capacity
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Unlimited Servers
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Server Backups
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    IP Bans
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Anti VPN/Proxy
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Verification Logs
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Auto Kick
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Vanity Url
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    User Role Backup
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    Customization
                </li>
                <li class="my-2 font-bold text-lg dark:text-gray-200">
                    <i class="fas fa-check text-indigo-600"></i>
                    API Access
                </li>
            </ul>

            <a href="/register"
               class="border border-indigo-600 block w-full rounded-lg p-3 hover:bg-indigo-600 text-indigo-600 hover:text-white transition-all">
                Select Plan
            </a>
        </div>
    </div>
</section>

<section class="pt-20 pb-20 px-10" id="stats">
    <h2 class="font-bold text-4xl text-center text-gray-900 dark:text-gray-200">Statistics</h2>

    <div class="cards mt-10 sm:grid sm:grid-cols-1 sm:gap-5 lg:grid-cols-3 xl:px-32">
        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-user"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Accounts</h4>
            <div id="accounts" class="animate-pulse h-5 w-12 bg-slate-700 rounded flex mx-auto"></div>
        </div>

        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-server"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Servers</h4>
            <div id="servers" class="animate-pulse h-5 w-12 bg-slate-700 rounded flex mx-auto"></div>
        </div>

        <div class="card bg-gray-100 px-5 pt-10 pb-5 mb-10 rounded-lg shadow-lg transition-all hover:shadow-sm text-center sm:mb-0 dark:bg-slate-800 dark:text-gray-300">
            <i class="text-white bg-indigo-600 p-5 rounded-full mb-5 fas fa-user-group"></i>
            <h4 class="font-bold text-xl text-gray-800 mb-2 dark:text-gray-200">Members</h4>
            <div id="members" class="animate-pulse h-5 w-12 bg-slate-700 rounded flex mx-auto"></div>
        </div>
    </div>
</section>

<footer class="pb-10">
    <ul class="flex items-center justify-center">
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-600 dark:text-gray-200"
               href="https://discord.gg/restore">Support
            </a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-600 dark:text-gray-200" href="#pricing">Pricing</a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-600 dark:text-gray-200"
               href="mailto:support@restorecord.com">Contact
            </a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-600 dark:text-gray-200"
               href="/terms">Terms
            </a>
        </li>
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-600 dark:text-gray-200" href="/privacy">Privacy</a>
        </li>
    </ul>

    <ul class="flex items-center justify-center my-5">
        <li class="mx-1">
            <a href="https://discord.gg/restore">
                <i class="text-gray-600 text-xl dark:hover:text-gray-400 hover:text-gray-900 dark:text-gray-200 transition-all cursor-pointer fab fa-discord"></i>
            </a>
        </li>
        <li class="mx-1">
            <a href="https://www.youtube.com/channel/UCdO4LjbTjSJWxP9VQg7ZNXw">
                <i class="text-gray-600 text-xl dark:hover:text-gray-400 hover:text-gray-900 dark:text-gray-200 transition-all cursor-pointer fab fa-youtube"></i>
            </a>
        </li>
        <li class="mx-1">
            <a href="https://twitter.com/restorecord">
                <i class="text-gray-600 text-xl dark:hover:text-gray-400 hover:text-gray-900 dark:text-gray-200 transition-all cursor-pointer fab fa-twitter"></i>
            </a>
        </li>
    </ul>

    <div class="text-center">
        <p class="text-gray-900 dark:text-gray-400"></p>
    </div>
</footer>
<script>
    window.addEventListener('load', function () {
        document.querySelector('footer.pb-10 .text-center p').innerText = 'Copyright © ' + new Date().getFullYear() + ' RestoreCord';

        setInterval(function func() {
            fetch('/api/v1/stats').then(function (r) {
                return r.json()
            }).then(function (d) {
                document.getElementById('accounts').innerHTML = `<p>${d.accounts}</p>`;
                document.getElementById('servers').innerHTML = `<p>${d.servers}</p>`;
                document.getElementById('members').innerHTML = `<p>${d.members}</p>`;
                document.querySelectorAll('div.animate-pulse.h-5.w-12.bg-slate-700.rounded.flex.mx-auto').forEach(function (e) {
                    e.removeAttribute('class');
                });
            }).catch(function (e) {
                console.log(e)
            })
            return func;
        }(), 5000)
    });

    if ("serviceWorker" in navigator) {
        window.addEventListener("load", function () {
            navigator.serviceWorker
                .register("/serviceWorker.js")
                .then(res => console.log("service worker registered"))
                .catch(err => console.log("service worker not registered", err))
        })
    }
</script>
</body>
</html>