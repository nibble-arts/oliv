Format and content definitions
for documentation files
-----------------------

*) The documentation files are stored in a plain text file, 80 characters wide
with fixed line lengths of 80 characters.

*) The title of the file is aligned in the center with a --- unterline.

*) Class documentations should have the following format:

			1.) Title
			2.) A short common description
			3.) path: the path, the class is stored in the OLIV directory
			4.) class attributes: the class attribute definitions
			
						attribute_type $attribute_name				short description
						
			5.) class methods: the class method definitions
			
						type method_name(type value[..,type value])
						-------------------------------------------
						Description of the method
						
								...

If there are changes to asign, either to the source code, or to the
documentation, a //TODO part can be inserted before the title sequenz on the
start of the file.
