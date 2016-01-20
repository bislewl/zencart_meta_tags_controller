INSTALLATION INSTRUCTIONS

1 - BACK UP YOUR FILES AND DATABASE
2 - Copy the contents of your current includes/languages/english/YOUR_TEMPLATE
2 - Rename YOUR_TEMPLATE & YOUR_ADMIN to the correct name
3 - Upload the files preserving the directory structure (includes/languages/english/YOUR_TEMPLATE will be overwritten)
4 - Load your Admin
5 - In the Admin go to Tools -> Meta Tags Controller
    a) To modify meta tags simply enter them into the boxes and click update (the page will not reload)
    b) Copy your Home Page Meta Tags (title,description,keywords) into the home tab
    c) Copy your Sitewide: Tagline, Description, and Keywords
    d) Create meta tags for any pages you wish to add (or may have already). contact_us, privacy, and conditions are already added for examples
        1) Click Add Page
        2) Enter the page name with with proper case Example "contact_us"
        3) Enter the group (tab) the meta tag should appear in
        4) Click Add, going to the General Tab the page should now appear
6 - NOTE for those users not in a superadmin status that you wish to be able to change meta tags will need to be granted access to both of the admin pages for the meta tags controller.

UNINSTALL INSTRUCTIONS
1 - Revert your saved includes/languages/english/YOUR_TEMPLATE file
1 - Remove the remaining the uploaded files
2 - Go to Tools -> Install SQL Patches and run the uninstall.sql

