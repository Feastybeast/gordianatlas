# Common Errors #

When attempting to run YII's framework construction tool, I receive the error:
```
'"php.exe" is not recognized as an internal or external command,
operable program or batch file.
```

See [DevelopmentErrorsAndFixes#PHP.exe\_not\_found\_in\_Command\_Line](DevelopmentErrorsAndFixes#PHP.exe_not_found_in_Command_Line.md)


# PHP.exe not found in Command Line #

**NOTE: You will need administrator access to perform this function, please contact site IT administrators for assistance.**

<font color='red'><b>NOTE: Be certain to add to rather than overwrite your path variable. Failure to follow this can result in all kinds of problems, and we will not be able to help you.</b></font>

This is most likely because PHP.exe isn't in your $PATH variable for windows. To resolve this error, first locate your PHP executable.
If you installed XAMPP, it will be located under
```
<your xampp root>/php/php.exe 
```
You will then need to update your windows $PATH variable (a guide can be found [here](http://geekswithblogs.net/renso/archive/2009/10/21/how-to-set-the-windows-path-in-windows-7.aspx) or just googling change windows $PATH), and close your command prompt program and reopen it for the changes to take effect.

You shouldn't encounter the error any longer, and   be able to run any previous commands depending on the knowledge of php.exe's location.