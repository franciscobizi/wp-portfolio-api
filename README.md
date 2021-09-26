# wp-portfolio-api
The Portifolio is small theme for creating and showing portfolios via API as well with WP default templates.


### Quick Start

Clone the repo or download zip file and go to wp-content/themes folder choose the the-portfolio theme. Install it on your project, install ACF Pro plugin and start creating your CPT. To use custom templates for Portfolios CPT go to the admin create new pages and choose from template attributes page the customs portfolios templates. The theme is very easy to use

### Requirements

- PHP >= 7.0
- ACF Pro Plugin

### Theme features structure

- API folder - located the REST API code for Portfolios CPT
- Classes folder - located code for registering Portfolio CPT
- ACF-JSON folder - located synch ACF groups fields
- portfolios-page.php and single-portfolio.php are custom pages for CPT

### Next

- Redesign templates
- Build header and footer components
- Add customizer options for design and styles
- portfolios-page.php and single-portfolio.php are custom pages for CPT

### Improvements 

- Move API and CPT to custom plugin

### Developers note

Most of the theme code are commented and could be generate documents by phpdoc for example. For testing REST API you can use the postman collection located on api folder at the theme.
