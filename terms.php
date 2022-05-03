<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RestoreCord - Terms</title>

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

    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/gh/alpinejs/alpine/dist/alpine.min.js" as="script">

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
    <h1 class="font-bold text-5xl mb-5 text-center lg:text-8xl text-gray-200">Terms of Service</h1>
    <div class="items-left justify-start text-left md:pl-24 md:pr-24 lg:pl-32 lg:pr-32">

        <p class="lg:text-2xl text-gray-100">1. AGREEMENT TO TERMS</p>
        <p class="lg:text-lg text-gray-300">These Terms of Use constitute a legally binding agreement
                                                               made between you, whether personally or on behalf of an entity (“you”) and RestoreCord ("Company," “we,"
                                                               “us," or “our”), concerning your access to and use of the
            <a
                    class="text-white border-b border-sky-400 font-semibold hover:border-b-2 transition-all"
                    href="https://restorecord.com">https://restorecord.com
            </a>
                                                               website as well as any
                                                               other media form, media channel, mobile website or mobile application related, linked, or otherwise
                                                               connected thereto (collectively, the “Site”). You agree that by accessing the Site, you have read,
                                                               understood, and agreed to be bound by all of these Terms of Use. IF YOU DO NOT AGREE WITH ALL OF THESE TERMS
                                                               OF USE, THEN YOU ARE EXPRESSLY PROHIBITED FROM USING THE SITE AND YOU MUST DISCONTINUE USE IMMEDIATELY.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">Supplemental terms and conditions or documents that may
                                                               be posted on the Site from time to time are hereby expressly incorporated herein by reference. We reserve
                                                               the right, in our sole discretion, to make changes or modifications to these Terms of Use from time to time.
                                                               We will alert you about any changes by updating the “Last updated” date of these Terms of Use, and you waive
                                                               any right to receive specific notice of each such change. Please ensure that you check the applicable Terms
                                                               every time you use our Site so that you understand which Terms apply. You will be subject to, and will be
                                                               deemed to have been made aware of and to have accepted, the changes in any revised Terms of Use by your
                                                               continued use of the Site after the date such revised Terms of Use are posted.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">The information provided on the Site is not intended for
                                                               distribution to or use by any person or entity in any jurisdiction or country where such distribution or use
                                                               would be contrary to law or regulation or which would subject us to any registration requirement within such
                                                               jurisdiction or country. Accordingly, those persons who choose to access the Site from other locations do so
                                                               on their own initiative and are solely responsible for compliance with local laws, if and to the extent
                                                               local laws are applicable.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">The Site is not tailored to comply with industry-specific
                                                               regulations (Health Insurance Portability and Accountability Act (HIPAA), Federal Information Security
                                                               Management Act (FISMA), etc.), so if your interactions would be subjected to such laws, you may not use this
                                                               Site. You may not use the Site in a way that would violate the Gramm-Leach-Bliley Act (GLBA).
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">The Site is intended for users who are at least 13 years
                                                               of age. All users who are minors in the jurisdiction in which they reside (generally under the age of 18)
                                                               must have the permission of, and be directly supervised by, their parent or guardian to use the Site. If you
                                                               are a minor, you must have your parent or guardian read and agree to these Terms of Use prior to you using
                                                               the Site.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">2. INTELLECTUAL PROPERTY RIGHTS</p>
        <p class="lg:text-lg text-gray-300">Unless otherwise indicated, the Site is our proprietary
                                                               property and all source code, databases, functionality, software, website designs, audio, video, text,
                                                               photographs, and graphics on the Site (collectively, the “Content”) and the trademarks, service marks, and
                                                               logos contained therein (the “Marks”) are owned or controlled by us or licensed to us, and are protected by
                                                               copyright and trademark laws and various other intellectual property rights and unfair competition laws of
                                                               the United States, international copyright laws, and international conventions. The Content and the Marks
                                                               are provided on the Site “AS IS” for your information and personal use only. Except as expressly provided in
                                                               these Terms of Use, no part of the Site and no Content or Marks may be copied, reproduced, aggregated,
                                                               republished, uploaded, posted, publicly displayed, encoded, translated, transmitted, distributed, sold,
                                                               licensed, or otherwise exploited for any commercial purpose whatsoever, without our express prior written
                                                               permission.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">Provided that you are eligible to use the Site, you are
                                                               granted a limited license to access and use the Site and to download or print a copy of any portion of the
                                                               Content to which you have properly gained access solely for your personal, non-commercial use. We reserve
                                                               all rights not expressly granted to you in and to the Site, the Content and the Marks.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">3. USER REPRESENTATIONS</p>
        <p class="lg:text-lg text-gray-300">By using the Site, you represent and warrant that: (1)
                                                               all registration information you submit will be true, accurate, current, and complete; (2) you will maintain
                                                               the accuracy of such information and promptly update such registration information as necessary; (3) you
                                                               have the legal capacity and you agree to comply with these Terms of Use; (4) you are not under the age of
                                                               13; (5) you are not a minor in the jurisdiction in which you reside, or if a minor, you have received
                                                               parental permission to use the Site; (6) you will not access the Site through automated or non-human means,
                                                               whether through a bot, script, or otherwise; (7) you will not use the Site for any illegal or unauthorized
                                                               purpose; and (8) your use of the Site will not violate any applicable law or regulation.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">If you provide any information that is untrue,
                                                               inaccurate, not current, or incomplete, we have the right to suspend or terminate your account and refuse
                                                               any and all current or future use of the Site (or any portion thereof).
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">4. USER REGISTRATION</p>
        <p class="lg:text-lg text-gray-300">You may be required to register with the Site. You agree
                                                               to keep your password confidential and will be responsible for all use of your account and password. We
                                                               reserve the right to remove, reclaim, or change a username you select if we determine, in our sole
                                                               discretion, that such username is inappropriate, obscene, or otherwise objectionable.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">5. PROHIBITED ACTIVITIES</p>
        <p class="lg:text-lg text-gray-300">You may not access or use the Site for any purpose other
                                                               than that for which we make the Site available. The Site may not be used in connection with any commercial
                                                               endeavors except those that are specifically endorsed or approved by us.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">As a user of the Site, you agree not to:</p>
        <p class="lg:text-lg text-gray-300">Systematically retrieve data or other content from the
                                                               Site to create or compile, directly or indirectly, a collection, compilation, database, or directory without
                                                               written permission from us.
        </p>
        <p class="lg:text-lg text-gray-300">Trick, defraud, or mislead us and other users, especially
                                                               in any attempt to learn sensitive account information such as user passwords.
        </p>
        <p class="lg:text-lg text-gray-300">Circumvent, disable, or otherwise interfere with
                                                               security-related features of the Site, including features that prevent or restrict the use or copying of any
                                                               Content or enforce limitations on the use of the Site and/or the Content contained therein.
        </p>
        <p class="lg:text-lg text-gray-300">Disparage, tarnish, or otherwise harm, in our opinion, us
                                                               and/or the Site.
        </p>
        <p class="lg:text-lg text-gray-300">Use any information obtained from the Site in order to
                                                               harass, abuse, or harm another person.
        </p>
        <p class="lg:text-lg text-gray-300">Make improper use of our support services or submit false
                                                               reports of abuse or misconduct.
        </p>
        <p class="lg:text-lg text-gray-300">Use the Site in a manner inconsistent with any applicable
                                                               laws or regulations.
        </p>
        <p class="lg:text-lg text-gray-300">Engage in unauthorized framing of or linking to the
                                                               Site.
        </p>
        <p class="lg:text-lg text-gray-300">Upload or transmit (or attempt to upload or to transmit)
                                                               viruses, Trojan horses, or other material, including excessive use of capital letters and spamming
                                                               (continuous posting of repetitive text), that interferes with any party's uninterrupted use and enjoyment of
                                                               the Site or modifies, impairs, disrupts, alters, or interferes with the use, features, functions, operation,
                                                               or maintenance of the Site.
        </p>
        <p class="lg:text-lg text-gray-300">Engage in any automated use of the system, such as using
                                                               scripts to send comments or messages, or using any data mining, robots, or similar data gathering and
                                                               extraction tools.
        </p>
        <p class="lg:text-lg text-gray-300">Delete the copyright or other proprietary rights notice
                                                               from any Content.
        </p>
        <p class="lg:text-lg text-gray-300">Attempt to impersonate another user or person or use the
                                                               username of another user.
        </p>
        <p class="lg:text-lg text-gray-300">Upload or transmit (or attempt to upload or to transmit)
                                                               any material that acts as a passive or active information collection or transmission mechanism, including
                                                               without limitation, clear graphics interchange formats (“gifs”), 1x1 pixels, web bugs, cookies, or other
                                                               similar devices (sometimes referred to as “spyware” or “passive collection mechanisms” or “pcms”).
        </p>
        <p class="lg:text-lg text-gray-300">Interfere with, disrupt, or create an undue burden on the
                                                               Site or the networks or services connected to the Site.
        </p>
        <p class="lg:text-lg text-gray-300">Harass, annoy, intimidate, or threaten any of our
                                                               employees or agents engaged in providing any portion of the Site to you.
        </p>
        <p class="lg:text-lg text-gray-300">Attempt to bypass any measures of the Site designed to
                                                               prevent or restrict access to the Site, or any portion of the Site.
        </p>
        <p class="lg:text-lg text-gray-300">Copy or adapt the Site's software, including but not
                                                               limited to Flash, PHP, HTML, JavaScript, or other code.
        </p>
        <p class="lg:text-lg text-gray-300">Except as permitted by applicable law, decipher,
                                                               decompile, disassemble, or reverse engineer any of the software comprising or in any way making up a part of
                                                               the Site.
        </p>
        <p class="lg:text-lg text-gray-300">Except as may be the result of standard search engine or
                                                               Internet browser usage, use, launch, develop, or distribute any automated system, including without
                                                               limitation, any spider, robot, cheat utility, scraper, or offline reader that accesses the Site, or using or
                                                               launching any unauthorized script or other software.
        </p>
        <p class="lg:text-lg text-gray-300">Use a buying agent or purchasing agent to make purchases
                                                               on the Site.
        </p>
        <p class="lg:text-lg text-gray-300">Make any unauthorized use of the Site, including
                                                               collecting usernames and/or email addresses of users by electronic or other means for the purpose of sending
                                                               unsolicited email, or creating user accounts by automated means or under false pretenses.
        </p>
        <p class="lg:text-lg text-gray-300">Use the Site as part of any effort to compete with us or
                                                               otherwise use the Site and/or the Content for any revenue-generating endeavor or commercial enterprise.
        </p>
        <p class="lg:text-lg text-gray-300">Sell or otherwise transfer your profile.</p>
        <p class="lg:text-lg text-gray-300">Violating any part of
            <a
                    class="text-white border-b border-sky-400 font-semibold hover:border-b-2 transition-all"
                    href="https://discord.com/terms">https://discord.com/terms
            </a>
                                                               and
            <a class="text-white border-b border-sky-400 font-semibold hover:border-b-2 transition-all"
               href="https://discord.com/guidelines">https://discord.com/guidelines
            </a>
        </p>
        <p class="lg:text-lg text-gray-300">Attacks against our webserver, such as DDoS attacks or
                                                               arbitrary code execution.
        </p>
        <p class="lg:text-lg text-gray-300">Attempting to libel RestoreCord with the intent of
                                                               hurting its reputation.
        </p>
        <p class="lg:text-lg text-gray-300">Making multiple accounts.</p>
        <p class="lg:text-lg text-gray-300">Sell or transfer of your members.</p>
        <br>

        <p class="lg:text-2xl text-gray-100">6. GUIDELINES FOR REVIEWS</p>
        <p class="lg:text-lg text-gray-300">We may provide you areas on the Site to leave reviews or
                                                               ratings. When posting a review, you must comply with the following criteria: (1) you should have firsthand
                                                               experience with the person/entity being reviewed; (2) your reviews should not contain offensive profanity,
                                                               or abusive, racist, offensive, or hate language; (3) your reviews should not contain discriminatory
                                                               references based on religion, race, gender, national origin, age, marital status, sexual orientation, or
                                                               disability; (4) your reviews should not contain references to illegal activity; (5) you should not be
                                                               affiliated with competitors if posting negative reviews; (6) you should not make any conclusions as to the
                                                               legality of conduct; (7) you may not post any false or misleading statements; and (8) you may not organize a
                                                               campaign encouraging others to post reviews, whether positive or negative.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">We may accept, reject, or remove reviews in our sole
                                                               discretion. We have absolutely no obligation to screen reviews or to delete reviews, even if anyone
                                                               considers reviews objectionable or inaccurate. Reviews are not endorsed by us, and do not necessarily
                                                               represent our opinions or the views of any of our affiliates or partners. We do not assume liability for any
                                                               review or for any claims, liabilities, or losses resulting from any review. By posting a review, you hereby
                                                               grant to us a perpetual, non-exclusive, worldwide, royalty-free, fully-paid, assignable, and sublicensable
                                                               right and license to reproduce, modify, translate, transmit by any means, display, perform, and/or
                                                               distribute all content relating to reviews.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">7. SUBMISSIONS</p>
        <p class="lg:text-lg text-gray-300">You acknowledge and agree that any questions, comments,
                                                               suggestions, ideas, feedback, or other information regarding the Site ("Submissions") provided by you to us
                                                               are non-confidential and shall become our sole property. We shall own exclusive rights, including all
                                                               intellectual property rights, and shall be entitled to the unrestricted use and dissemination of these
                                                               Submissions for any lawful purpose, commercial or otherwise, without acknowledgment or compensation to you.
                                                               You hereby waive all moral rights to any such Submissions, and you hereby warrant that any such Submissions
                                                               are original with you or that you have the right to submit such Submissions. You agree there shall be no
                                                               recourse against us for any alleged or actual infringement or misappropriation of any proprietary right in
                                                               your Submissions.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">8. THIRD-PARTY WEBSITE AND CONTENT</p>
        <p class="lg:text-lg text-gray-300">The Site may contain (or you may be sent via the Site)
                                                               links to other websites ("Third-Party Websites") as well as articles, photographs, text, graphics, pictures,
                                                               designs, music, sound, video, information, applications, software, and other content or items belonging to
                                                               or originating from third parties ("Third-Party Content"). Such Third-Party Websites and Third-Party Content
                                                               are not investigated, monitored, or checked for accuracy, appropriateness, or completeness by us, and we are
                                                               not responsible for any Third-Party Websites accessed through the Site or any Third-Party Content posted on,
                                                               available through, or installed from the Site, including the content, accuracy, offensiveness, opinions,
                                                               reliability, privacy practices, or other policies of or contained in the Third-Party Websites or the
                                                               Third-Party Content. Inclusion of, linking to, or permitting the use or installation of any Third-Party
                                                               Websites or any Third-Party Content does not imply approval or endorsement thereof by us. If you decide to
                                                               leave the Site and access the Third-Party Websites or to use or install any Third-Party Content, you do so
                                                               at your own risk, and you should be aware these Terms of Use no longer govern. You should review the
                                                               applicable terms and policies, including privacy and data gathering practices, of any website to which you
                                                               navigate from the Site or relating to any applications you use or install from the Site. Any purchases you
                                                               make through Third-Party Websites will be through other websites and from other companies, and we take no
                                                               responsibility whatsoever in relation to such purchases which are exclusively between you and the applicable
                                                               third party. You agree and acknowledge that we do not endorse the products or services offered on
                                                               Third-Party Websites and you shall hold us harmless from any harm caused by your purchase of such products
                                                               or services. Additionally, you shall hold us harmless from any losses sustained by you or harm caused to you
                                                               relating to or resulting in any way from any Third-Party Content or any contact with Third-Party Websites.
        <p class="lg:text-lg text-gray-300">
            <br>

        <p class="lg:text-2xl text-gray-100">9. SITE MANAGEMENT</p>
        <p class="lg:text-lg text-gray-300">We reserve the right, but not the obligation, to: (1)
                                                               monitor the Site for violations of these Terms of Use; (2) take appropriate legal action against anyone who,
                                                               in our sole discretion, violates the law or these Terms of Use, including without limitation, reporting such
                                                               user to law enforcement authorities; (3) in our sole discretion and without limitation, refuse, restrict
                                                               access to, limit the availability of, or disable (to the extent technologically feasible) any of your
                                                               Contributions or any portion thereof; (4) in our sole discretion and without limitation, notice, or
                                                               liability, to remove from the Site or otherwise disable all files and content that are excessive in size or
                                                               are in any way burdensome to our systems; and (5) otherwise manage the Site in a manner designed to protect
                                                               our rights and property and to facilitate the proper functioning of the Site.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">10. PRIVACY POLICY</p>
        <p class="lg:text-lg text-gray-300">We care about data privacy and security. Please review
                                                               our Privacy Policy:
            <a
                    class="text-white border-b border-sky-400 font-semibold hover:border-b-2 transition-all"
                    href="https://restorecord.com/privacy/">https://restorecord.com/privacy/
            </a>
                                                               . By using the Site, you
                                                               agree to be bound by our
                                                               Privacy Policy, which is incorporated into these Terms of Use. Please be advised the Site is hosted in
                                                               United Kingdom. If you access the Site from any other region of the world with laws or other requirements
                                                               governing personal data collection, use, or disclosure that differ from applicable laws in United Kingdom,
                                                               then through your continued use of the Site, you are transferring your data to United Kingdom, and you agree
                                                               to have your data transferred to and processed in United Kingdom. Further, we do not knowingly accept,
                                                               request, or solicit information from children or knowingly market to children. Therefore, in accordance with
                                                               the U.S. Children's Online Privacy Protection Act, if we receive actual knowledge that anyone under the age
                                                               of 13 has provided personal information to us without the requisite and verifiable parental consent, we will
                                                               delete that information from the Site as quickly as is reasonably practical.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">11. TERM AND TERMINATION</p>
        <p class="lg:text-lg text-gray-300">These Terms of Use shall remain in full force and effect
                                                               while you use the Site. WITHOUT LIMITING ANY OTHER PROVISION OF THESE TERMS OF USE, WE RESERVE THE RIGHT TO,
                                                               IN OUR SOLE DISCRETION AND WITHOUT NOTICE OR LIABILITY, DENY ACCESS TO AND USE OF THE SITE (INCLUDING
                                                               BLOCKING CERTAIN IP ADDRESSES), TO ANY PERSON FOR ANY REASON OR FOR NO REASON, INCLUDING WITHOUT LIMITATION
                                                               FOR BREACH OF ANY REPRESENTATION, WARRANTY, OR COVENANT CONTAINED IN THESE TERMS OF USE OR OF ANY APPLICABLE
                                                               LAW OR REGULATION. WE MAY TERMINATE YOUR USE OR PARTICIPATION IN THE SITE OR DELETE YOUR ACCOUNT AND ANY
                                                               CONTENT OR INFORMATION THAT YOU POSTED AT ANY TIME, WITHOUT WARNING, IN OUR SOLE DISCRETION.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">If we terminate or suspend your account for any reason,
                                                               you are prohibited from registering and creating a new account under your name, a fake or borrowed name, or
                                                               the name of any third party, even if you may be acting on behalf of the third party. In addition to
                                                               terminating or suspending your account, we reserve the right to take appropriate legal action, including
                                                               without limitation pursuing civil, criminal, and injunctive redress.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">12. MODIFICATIONS AND INTERRUPTIONS</p>
        <p class="lg:text-lg text-gray-300">We reserve the right to change, modify, or remove the
                                                               contents of the Site at any time or for any reason at our sole discretion without notice. However, we have
                                                               no obligation to update any information on our Site. We also reserve the right to modify or discontinue all
                                                               or part of the Site without notice at any time. We will not be liable to you or any third party for any
                                                               modification, price change, suspension, or discontinuance of the Site.
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">We cannot guarantee the Site will be available at all
                                                               times. We may experience hardware, software, or other problems or need to perform maintenance related to the
                                                               Site, resulting in interruptions, delays, or errors. We reserve the right to change, revise, update,
                                                               suspend, discontinue, or otherwise modify the Site at any time or for any reason without notice to you. You
                                                               agree that we have no liability whatsoever for any loss, damage, or inconvenience caused by your inability
                                                               to access or use the Site during any downtime or discontinuance of the Site. Nothing in these Terms of Use
                                                               will be construed to obligate us to maintain and support the Site or to supply any corrections, updates, or
                                                               releases in connection therewith.
        </p>
        <br>
        <p class="lg:text-2xl text-gray-100">13. USER DATA</p>
        <p class="lg:text-lg text-gray-300">We will maintain certain data that you transmit to the
                                                               Site for the purpose of managing the performance of the Site, as well as data relating to your use of the
                                                               Site. Although we perform regular routine backups of data, you are solely responsible for all data that you
                                                               transmit or that relates to any activity you have undertaken using the Site. You agree that we shall have no
                                                               liability to you for any loss or corruption of any such data, and you hereby waive any right of action
                                                               against us arising from any such loss or corruption of such data.
        </p>
        <br>
        <p class="lg:text-2xl text-gray-100">14. MISCELLANEOUS</p>
        <p class="lg:text-lg text-gray-300">These Terms of Use and any policies or operating rules
                                                               posted by us on the Site or in respect to the Site constitute the entire agreement and understanding between
                                                               you and us. Our failure to exercise or enforce any right or provision of these Terms of Use shall not
                                                               operate as a waiver of such right or provision. These Terms of Use operate to the fullest extent permissible
                                                               by law. We may assign any or all of our rights and obligations to others at any time. We shall not be
                                                               responsible or liable for any loss, damage, delay, or failure to act caused by any cause beyond our
                                                               reasonable control. If any provision or part of a provision of these Terms of Use is determined to be
                                                               unlawful, void, or unenforceable, that provision or part of the provision is deemed severable from these
                                                               Terms of Use and does not affect the validity and enforceability of any remaining provisions. There is no
                                                               joint venture, partnership, employment or agency relationship created between you and us as a result of
                                                               these Terms of Use or use of the Site. You agree that these Terms of Use will not be construed against us by
                                                               virtue of having drafted them. You hereby waive any and all defenses you may have based on the electronic
                                                               form of these Terms of Use and the lack of signing by the parties hereto to execute these Terms of Use.
        </p>
        <br>

        <p class="lg:text-2xl text-gray-100">15. CONTACT US</p>
        <p class="lg:text-lg text-gray-300">In order to resolve a complaint regarding the Site or to
                                                               receive further information regarding use of the Site, please contact us at:
        </p>
        <br>
        <p class="lg:text-lg text-gray-300">Phone:
            <a
                    class="text-white border-b border-sky-400 font-semibold hover:border-b-2 transition-all"
                    href="tel:+15083883458">+1 (508)-388-3458
            </a>
        </p>
        <p class="lg:text-lg text-gray-300">Email:
            <a
                    class="text-white border-b border-sky-400 font-semibold hover:border-b-2 transition-all"
                    href="mailto:support@restorecord.com">support@restorecord.com
            </a>
        </p>
    </div>
</section>

<footer class="pb-10">
    <ul class="flex items-center justify-center">
        <li class="mx-2 sm:mx-0">
            <a class="md:pr-4 md:pl-4 pr-1 text-gray-200"
               href="https://discord.gg/restorecordbot">Support
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
            <a href="https://discord.gg/restorecordbot">
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