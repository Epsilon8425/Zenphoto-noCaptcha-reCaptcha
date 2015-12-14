# Zenphoto-noCaptcha-reCaptcha

Description:
Zenphoto plugin to add noCaptcha reCaptcha functionality to forms.

Instructions:
1) Add nocapture_recapture.php to Zenphoto plugins folder. 
2) Enable nocaptcha_recaptcha plugin (can be found under Plugins > Spam > nocaptcha_recaptcha). You must disable other reCaptcha plugins before nocaptcha_recaptcha can be enabled.
3) Get reCaptcha keys from here: https://www.google.com/recaptcha/intro/index.html
4) Edit nocaptcha_recaptcha options (can be found under Options > Plugins > nocaptcha_recaptcha)
5) Enable CAPTCHA option in other plugins, I have tested with the following plugins:
   * register_user
   * contact_form

Changelog:

v1.0.1:
  * Updated default for secondary verification
  * Fixed spelling mistakes in comments
  * Updated the descriptions for some options
  * Updated README with plugin instructions and changelog

v1.0.0 Initial Release
