<?php

########################################################################
# Extension Manager/Repository config file for ext "google_services".
#
# Auto generated 03-05-2015 18:55
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF['google_services'] = array(
    'title'        => 'Google Services',
    'description'  => 'A package of usefully Google Services as library for other extensions or directly use: Google Sitemaps incl. different Sitemap Provider, Google Verify, Google Analytics, Google Document Viewer',
    'category'     => 'plugin',
    'version'      => '0.4.2',
    'state'        => 'stable',
    'author'       => 'Tim Lochmueller',
    'author_email' => 'webmaster@fruit-lab.de',
    'constraints'  => array(
        'depends' => array(
            'typo3' => '6.2.0-7.99.99',
        ),
    ),
);