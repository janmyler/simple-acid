# Require any additional compass plugins here.
require "compass-normalize"

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "css"
sass_dir = "sass"
images_dir = "img"
javascripts_dir = "js"

# output_style = :expanded or :nested or :compact or :compressed
output_style = (environment == :production) ? :compressed : :compact

# To disable debugging comments that display the original location of your selectors. Uncomment:
line_comments = false

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true
