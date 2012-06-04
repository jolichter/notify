<?php
/**
 * Notify snippet
 *
 * Copyright 2012 Bob Ray <http:bobsguides.com>
 *
 * @author Bob Ray <http:bobsguides.com>
 *
 * Notify is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * Notify is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Notify; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package notify
 */

/**
 * MODx Notify plugin
 *
 * Description: Removes all vestiges of Notify package except the files
 *
 *
 * @package notify
 *
 */

/* Properties:
 * @property &urlShorteningService list -- Service used to shorten all
 *    URLs in text and Tweet; Default: none.
 * @property &groups textfield -- Comma-separated list of User Groups
 *    to send to (no spaces). The Subscribers group will be set in the form,
 *    but if you delete it and submit with the Groups field empty, email
 *    will be sent to all users on the site; Default: (empty)..
 * @property &tags textfield -- (optional) Comma-separated list of
 *    tags (no spaces). If set, only users in specified Groups with the
 *    interest(s) set will receive the email; Default: (empty)..
 * @property &notifyFacebook combo-boolean -- Notify Facebook via
 *    Twitter with #fb in tweet -- must be set up in the Facebook Twitter
 *    App; Default: (empty)..
 * @property &twitterConsumerKey textfield -- Twitter Consumer Key;
 *    Default: (empty)..
 * @property &twitterConsumerSecret textfield -- Twitter Consumer
 *    Secret; Default: (empty)..
 * @property &twitterOauthToken textfield -- Twitter Access Token;
 *    Default: (empty)..
 * @property &twitterOauthSecret textfield -- Twitter Access Token
 *    Secret; Default: (empty)..
 * @property &bitlyApiKey textfield -- bit.ly API key (required);
 *    Default: (empty)..
 * @property &bitlyUsername textfield -- bit.ly username (required);
 *    Default: (empty)..
 * @property &googleApiKey textfield -- Google API key; Default:
 *    (empty)..
 * @property &suprApiKey textfield -- StumbleUpon API key (optional);
 *    Default: (empty)..
 * @property &suprUsername textfield -- Stumble Upon Username
 *    (optional); Default: (empty)..
 * @property &tinyurlApiKey textfield -- TinyUrl API key (optional);
 *    Default: (empty)..
 * @property &tinyurlUsername textfield -- TinyUrl username
 *    (optional); Default: (empty)..
 * @property &mailFrom textfield -- (optional) MAIL_FROM setting for
 *    email; Default: emailsender System Setting.
 * @property &mailFromName textfield -- (optional) MAIL_FROM_NAME
 *    setting for email; Default: site_name System Setting.
 * @property &mailSender textfield -- (optional) EMAIL_SENDER setting
 *    for email; Default: emailsender System Setting.
 * @property &mailReplyTo textfield -- (optional) REPLY_TO setting for
 *    email; Default: emailsender System Setting.
 * @property &nfFormTpl textfield -- Name of chunk to use for the
 *    Notify form; Default: NfNotifyFormTpl.
 * @property &nfEmailTpl textfield -- Name of chunk to use for the
 *    Email to send to subscribers; Default: NfSubscriberEmailTpl.
 * @property &nfTweetTpl textfield -- Name of chunk to use for the
 *    Tweet text; Default: nfTweetTpl.
 * @property &nfSubjectTpl textfield -- Name of chunk to use for the
 *    Email subject; Default: NfEmailSubjectTpl.
 * @property &nfTestEmailAddress textfield -- (optional) Email address
 *    for test email; Default: emailsender System Setting.
 * @property &sortBy textfield -- (optional) Field to sort by when
 *    selecting users; Default: username.
 * @property &userClass textfield -- (optional) class of the user
 *    object. Only necessary if you have subclassed the user object;
 *    Default: modUser.
 * @property &sortByAlias textfield -- (optional) class of the user
 *    object. Only necessary if you have subclassed the user object;
 *    Default: modUser.
 * @property &profileAlias textfield -- (optional) class of the user
 *    profile object. Only necessary if you have subclassed the user profile
 *    object; Default: modUserProfile.
 * @property &profileClass textfield -- (optional) class of the user
 *    profile object. Only necessary if you have subclassed the user profile
 *    object; Default: modUser.
 * @property &batchSize textfield -- (optional) Batch size for bulk
 *    email to subscribers; Default: 50.
 * @property &batchDelay textfield -- (optional) Delay between batches
 *    in seconds; Default: 1.
 * @property &itemDelay textfield -- (optional) Delay between
 *    individual emails in seconds; Default: .51.
 */

/* @var $modx modX */
/* @var $scriptProperties array */
/* @var $category modCategory */
/* @var $tv modTemplateVar */
/* @var $chunk modChunk */
/* @var $plugin modPlugin */
/* @var $pluginEvent modPluginEvent */
/* @var $propertySet modPropertySet */
/* @var $nameSpace modNameSpace */
/* @var $elementPropertySet modElementPropertySet */



/* abort if not previewing from 'mgr' */
if (! $modx->user->hasSessionContext('mgr') || ! $modx->user->isMember('Administrator')) {
    return $modx->lexicon('Unauthorized Access');
}
$output = '';
require_once $modx->getOption('nf.core_path', null, $modx->getOption('core_path') . 'components/notify/') . 'model/notify/notify.class.php';

$sp =& $scriptProperties;
$nf = new Notify($modx, $sp);

if (isset($_POST['submitVar']) && ($_POST['submitVar'] == 'submitVar')) {
    /* Handle repost */
    $output .= $nf->init('handleSubmission');
    $output = $nf->displayErrors() . $nf->displaySuccessMessages() . $output;
} else {
    /* Display form */
    /* @var $res modResource */

    $nf->init('displayForm');
    if ($nf->hasErrors()) {
        $output = $nf->displayErrors();
    } else {
        $output = $nf->displayForm();
    }
}

return $output;