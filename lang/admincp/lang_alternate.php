<?php

//Language variables used for AdminCP/Alternate Page

$lang['default_title'] = "Manage Adoptable Alternate Forms";
$lang['default'] = "Here you can add or edit the altertnate forms that are available to your adoptables.
				    This is a list of adoptable alternate forms available";
$lang['default_none'] = "Currently there is no alternate forms available.";
$lang['add_title'] = "Create a new adoptable alternate form";
$lang['add'] = "This page allows you to create a new alternate form that will be available to your adoptables.
				Please fill in the form below and hit the <i>Create Alt-Form</i> button below when you're ready.<br><br>";
$lang['adopt_explain'] = "(Choose which adoptable this alt-form belongs to)";
$lang['image_explain'] = "(Use a full image path, beginning with http://)";
$lang['level_explain'] = "(If specified, the selected adoptable will start to get this alt-form at or above specified level)";
$lang['item_explain'] = "(If specified, the selected adoptable will change to this alt-form when specified item is used)";
$lang['gender_explain'] = "(If specified, the selected adoptable will change to this alt-form only if gender is matched)";
$lang['lastalt_explain'] = "(If specified, the selected adoptable will change to this alt-form only if the current alt-form matches the specified alt ID. Alt ID can be copied from the table from last page.)";
$lang['chance_explain'] = "(Enter a value for n% chance for this alternate form to appear, ie. a value of 5 means 5% chance. It's necessary to make sure that chances for all alternative forms add up to no more than 100.)";
$lang['added_title'] = "Adoptable Alternate Form created Successfully";
$lang['added'] = "A new alternate form has been added to the database successfully. You may <a href='../'>go back to the Admin CP index page</a>.";
$lang['manage_title'] = "Manage an adoptable's Alternative Forms";
$lang['manage'] = "Here you can edit alternative forms for existing adoptables. Please select the adoptable whose alternative forms you wish to edit. The primary form is also shown above the corresponding alternate forms for each level as reference.<br />";
$lang['manage_alternate'] = "Managing Alternate Forms for {$mysidia->input->post("type")} adoptables";
$lang['manage_explain'] = "The table below shows all available alternative forms for your adoptable, select one to edit. <br /><br />";
$lang['nonexist'] = "The selected adoptables does not have alternative forms yet";
$lang['edit_title'] = "Edit Adoptable Alternate Form";
$lang['edit'] = "This page allows you to edit an adoptable alternate form that you have created.
				 Please fill in the form below and hit the <i>Edit Alt-form</i> button below when you're ready.";
$lang['nonexist'] = "The Item does not exist in database.";
$lang['edited_title'] = "Adoptable edited Successfully";
$lang['edited'] = "The alternate form has been edit successfully. You may <a href='../../../'>go back to the Admin CP index page</a>.";
$lang['delete_title'] = "Adoptable edited Deleted";
$lang['delete'] = "You have successfully removed this adoptable alternate form from database.";
$lang['adopt'] = "You did not specify what adoptable this alternate form belongs to. Please go back and try again.";
$lang['images'] = "You did not select an image for this adoptable alternate form. Please go back and make sure an image is selected.";
$lang['chance'] = "You did not enter the chance of this alternate form being selected for your adoptable. Please go back and try again.";
