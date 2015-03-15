# Introduction #

The goal of this page is to fully document the Gordian\_auth framework components, from the database layer up to the smallest helpers.

# Concepts #

# Tables #

# The Model #

# The Library #

# The Helpers (/application/helpers/gordian\_auth\_helper.php) #

**gordian\_auth\_user\_widget(_array $config_)**: The core user registration and management.

| **key** | **options** |
|:--------|:------------|
| header | Expected to be overridden. This is the title that displays above the main UI. |
| email | The widget row providing the email input field for user registration and login behaviors. May **NOT** be disabled. Providing a value for this key will override the default label. |
| nickname | The widget row providing the nickname input field for user registration and editing behaviors. May be disabled by setting the value to FALSE. Providing a value for this key will overide the default label. |
| password | The widget row providing the password input field for user registration and login behaviors. May **NOT** be disabled. Providing a value for this key will override the default label. |
| confirm | The widget row providing the confirmation input field for user registration behaviors. May be disabled by setting the value to FALSE. Providing a value for this key will overide the default label. |
| Button | The label that appears on the button at the bottom of the UI. May NOT be disabled. Providing a value for this key will override the default label. |
| register | The link routing users to the registration screen. May be disabled by setting the value to FALSE. Providing a value for this key will override the default label. |
| login | The link routing users to the registration screen. May be disabled by setting the value to FALSE. Providing a value for this key will override the default label. |
| forgot | The link routing users to the registration screen. May be disabled by setting the value to FALSE. Providing a value for this key will override the default label. |