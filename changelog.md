# Change Log
All notable changes to this project will be documented in this file.
 
## [1.2] - 2021-11-16

### Added
- adds sandbox numbers validation
 
### Fixed
- fixes the table from 'wp_vpos_woocommerce_transacations' to 'wp_vpos_woocommerce_transactions'

## [1.1] - 2021-06-01

### Added
- create db for local transactions
- automatically create polling and checkout page
- automatically insert mobile number to checkout page to improve user experience
 
### Changed
- implement mechanism to update payment via callback instead of polling
 
### Removed
- Removed unecessary options from plugin settings (page_id, merchant name, supervisor card)
- acquire merchant name from wordpress blogname function instead of merchant name setting.
- redirect to checkout page using vpos checkout page permalink ("vpos_checkout") instead of page id.
 


 