###
# Compass
###

# Susy grids in Compass
# First: gem install susy --pre
# require 'susy'

# Change Compass configuration
# compass_config do |config|
#   config.output_style = :compact
# end

###
# Page options, layouts, aliases and proxies
###

# Per-page layout changes:
#
# With no layout
# page "/path/to/file.html", :layout => false
#
# With alternative layout
# page "/path/to/file.html", :layout => :otherlayout
#
# A path which all have the same layout
# with_layout :admin do
#   page "/admin/*"
# end

# Proxy (fake) files
# page "/this-page-has-no-template.html", :proxy => "/template-file.html" do
#   @which_fake_page = "Rendering a fake page with a variable"
# end

###
# Helpers
###

# Automatic image dimensions on image_tag helper
# activate :automatic_image_sizes

# Methods defined in the helpers block are available in templates
# helpers do
#   def some_helper
#     "Helping"
#   end
# end

#if @release is false, the build command will include docs
@release = false

@colorList = ["Aero blue","Air Force blue (RAF)","Air Force blue (USAF)","Air superiority blue","Alabama Crimson","Alice blue","Alizarin crimson","Alloy orange","Almond","Amaranth","Amazon","Amber",
              "SAE/ECE Amber (color)","American rose","Amethyst","Android Green","Anti-flash white","Antique brass","Antique bronze","Antique fuchsia "]

helpers do

  def icons
    File.readlines('icons.txt')
  end

  def extra_button_styles(with = nil)
    %w(large small mini)
  end

  def button_styles
    %w(normal large small mini)
  end

  def button_colors
    %w(default red blue green gray black lightblue gold sea brown)
  end

  def random_numbers(count, from=3, to=30)
    count.times.map{ from + Random.rand(to-from) }
  end

  def random_string(length=10)
    chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789'
    password = ''
    length.times { password << chars[rand(chars.size)] }
    password
  end

  def current_link
    root_path = "pages/"
    current_route = request.path.sub(root_path, "").split("/") # pages/dashboard/stats.html -> ['dashboard', 'stats.html']
    return current_route.first, current_route.last.split(".").first
  end

  def link_level
    primary, secondary = current_link
    if menu[primary.to_sym][:items].keys.count > 1
      return 2
    else
      return 1
    end
  end

  def nav_collapse(options = {})
    @primary, @secondary = current_link
    return "collapsed" unless options[:primary] == @primary
  end

  def nav_active(options = {})
    @primary, @secondary = current_link

    if options[:primary]
      return "active" if options[:primary] == @primary
    end

    if options[:secondary]
      return "active" if options[:secondary] == @secondary
    end
  end

  def menu
    return {
        dashboard: {
            primary: { link: "dashboard", icon: "icon-dashboard", label: "Dashboard" },
            items: {
                dashboard: { icon: "icon-dashboard", label: "Dashboard" }
            }
        },
        ui_lab: {
            primary: { link: "buttons", icon: "icon-beaker", label: "UI Lab" },
            items: {
                buttons: { icon: "icon-hand-up",       label: "Buttons" },
                general: { icon: "icon-beaker",        label: "General elements" },
                icons:   { icon: "icon-info-sign",     label: "Icons"},
                grid:    { icon: "icon-th-large",      label: "Grid"},
                tables:  { icon: "icon-table",         label: "Tables"},
                widgets: { icon: "icon-plus-sign-alt", label: "Widgets"},
            }
        },
        forms: {
            primary: { link: "forms", icon: "icon-edit", label: "Forms" },
            items: {
                forms: { icon: "icon-edit", label: "Form Elements" }
            }
        },
        charts: {
            primary: { link: "charts", icon: "icon-bar-chart", label: "Charts"},
            items: {
                charts: { icon: "icon-bar-chart", label: "Charts"}
            }
        },
        other: {
            primary: { link: "wizard", icon: "icon-link", label: "Others"},
            items: {
                wizard: { icon: "icon-magic", label: "Wizard" },
                login: { icon: "icon-user", label: "Login Page" },
                sign_up: { icon: "icon-user", label: "Sign Up Page" },
                full_calendar: { icon: "icon-calendar", label: "Full Calendar" },
                error404: { icon: "icon-ban-circle", label: "Error 404 page" },
            }
        }
    }
  end

  def crumbs
    primary, secondary = current_link
    return {
        primary:   { icon: menu[primary.to_sym][:primary][:icon], label: menu[primary.to_sym][:primary][:label]},
        secondary: {
            icon:  menu[primary.to_sym][:items][secondary.to_sym][:icon],
            label: menu[primary.to_sym][:items][secondary.to_sym][:label]
        }
    }
  end

end

set :css_dir, 'stylesheets'

set :js_dir, 'javascripts'

set :images_dir, 'images'

#set :debug_assets, true

# Build-specific configuration
configure :build do
  # For example, change the Compass output style for deployment
  activate :minify_css

  # Minify Javascript on build
  activate :minify_javascript

  # Enable cache buster
  # activate :cache_buster

  # Use relative URLs
  activate :relative_assets

  # Compress PNGs after build
  # First: gem install middleman-smusher
  # require "middleman-smusher"
  # activate :smusher

  # Or use a different image path
  # set :http_path, "/Content/images/"
end
