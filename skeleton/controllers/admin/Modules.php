<?php
/**
 * CodeIgniter Skeleton
 *
 * A ready-to-use CodeIgniter skeleton  with tons of new features
 * and a whole new concept of hooks (actions and filters) as well
 * as a ready-to-use and application-free theme and plugins system.
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2018, Kader Bouyakoub <bkader@mail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package 	CodeIgniter
 * @author 		Kader Bouyakoub <bkader@mail.com>
 * @copyright	Copyright (c) 2018, Kader Bouyakoub <bkader@mail.com>
 * @license 	http://opensource.org/licenses/MIT	MIT License
 * @link 		https://goo.gl/wGXHO9
 * @since 		2.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Modules Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author 		Kader Bouyakoub <bkader@mail.com>
 * @link 		https://goo.gl/wGXHO9
 * @copyright 	Copyright (c) 2018, Kader Bouyakoub (https://goo.gl/wGXHO9)
 * @since 		2.0.0
 * @version 	2.0.0
 */
class Modules extends Admin_Controller {

	/**
	 * __construct
	 *
	 * Load needed files.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	2.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function __construct()
	{
		// Call parent constructor.
		parent::__construct();

		$this->load->language('csk_modules');

		// Add our head string.
		$this->_jquery_sprintf();
		add_filter('admin_head', array($this, '_admin_head'));

		// Add modules scripts.
		$this->scripts[] = 'modules';

		// Default page title and icon.
		$this->data['page_icon']  = 'cubes';
		$this->data['page_title'] = line('CSK_MODULES_MODULES');
	}

	// ------------------------------------------------------------------------

	/**
	 * index
	 *
	 * List all available modules.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	2.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function index()
	{
		$modules = $this->router->list_modules(true);

		foreach ($modules as $folder => &$m)
		{
			// Add module actions.
			$m['actions'] = array();

			if (true === $m['enabled'] && true === $m['has_settings'])
			{
				$m['actions'][] = html_tag('a', array(
					'href'  => admin_url('settings/'.$folder),
					'class' => 'btn btn-default btn-xs btn-icon ml-2',
				), fa_icon('cogs').line('CSK_MODULES_SETTINGS'));
			}

			if (true === $m['enabled'])
			{
				$m['actions'][] = html_tag('button', array(
					'type' => 'button',
					'data-endpoint' => nonce_ajax_url(
						'modules/deactivate/'.$folder,
						'deactivate-module_'.$folder
					),
					'class' => 'btn btn-default btn-xs btn-icon module-deactivate ml-2',
				), fa_icon('times text-danger').line('CSK_MODULES_DEACTIVATE'));
			}
			else
			{
				$m['actions'][] = html_tag('button', array(
					'type' => 'button',
					'data-endpoint' => nonce_ajax_url(
						'modules/activate/'.$folder,
						'activate-module_'.$folder
					),
					'class' => 'btn btn-default btn-xs btn-icon module-activate ml-2',
				), fa_icon('check text-success').line('CSK_MODULES_ACTIVATE'));
			}

			$m['actions'][] = html_tag('button', array(
				'type' => 'button',
				'data-endpoint' => nonce_ajax_url(
					'modules/delete/'.$folder,
					'delete-module_'.$folder
				),
				'class' => 'btn btn-default btn-xs btn-icon module-delete ml-2',
			), fa_icon('trash-o text-danger').line('CSK_MODULES_DELETE'));

			// Module details.
			$details = array();

			if ( ! empty($m['version'])) {
				$details[] = sprintf(line('CSK_MODULES_VERSION_NUM'), $m['version']);
			}
			if ( ! empty($m['author'])) {
				$author = (empty($m['author_uri'])) 
					? $m['author'] 
					: sprintf(line('CSK_MODULES_AUTHOR_URI'), $m['author'], $m['author_uri']);
				$details[] = sprintf(line('CSK_MODULES_AUTHOR_NAME'), $author);
			}
			if ( ! empty($m['license'])) {
				$license = empty($m['license_uri'])
					? $m['license']
					: sprintf(line('CSK_MODULES_LICENSE_URI'), $m['license'], $m['license_uri']);
				$details[] = sprintf(line('CSK_MODULES_LICENSE_NAME'), $license);
				// Reset license.
				$license = null;
			}
			if ( ! empty($m['module_uri'])) {
				$details[] = html_tag('a', array(
					'href'   => $m['module_uri'],
					'target' => '_blank',
					'rel'    => 'nofollow',
				), line('CSK_ADMIN_BTN_WEBSITE'));
			}
			if ( ! empty($m['author_email'])) {
				$details[] = sprintf(
					line('CSK_MODULES_AUTHOR_EMAIL_URI'),
					$m['author_email'],
					rawurlencode('Support: '.$m['name'])
				);
			}

			$m['details'] = $details;
		}

		$this->data['modules'] = $modules;
		
		$this->theme
			->set_title(line('CSK_MODULES_MODULES'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * install
	 *
	 * Method for installing modules from future server or upload ZIP modules.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	2.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function install()
	{
		// We prepare form validation.
		$this->prep_form();

		// Set page title and load view.
		$this->theme
			->set_title(line('CSK_MODULES_ADD'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * upload
	 *
	 * Method for uploading modules using ZIP archives.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	2.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function upload()
	{
		// We check CSRF token validity.
		if ( ! $this->check_nonce('upload-module'))
		{
			set_alert(line('CSK_ERROR_NONCE_URL'), 'error');
			redirect('admin/modules/install');
			exit;
		}

		// Did the user provide a valid file?
		if (empty($_FILES['modulezip']['name']))
		{
			set_alert(line('CSK_MODULES_ERROR_UPLOAD'), 'error');
			redirect('admin/modules/install');
			exit;
		}

		// Load our file helper and make sure the "unzip_file" function exists.
		$this->load->helper('file');
		if ( ! function_exists('unzip_file'))
		{
			set_alert(line('CSK_MODULES_ERROR_UPLOAD'), 'error');
			redirect('admin/modules/install');
			exit;
		}

		// Load upload library.
		$this->load->library('upload', array(
			'upload_path'   => FCPATH.'content/uploads/temp/',
			'allowed_types' => 'zip',
		));

		// Error uploading?
		if (false === $this->upload->do_upload('modulezip') 
			OR ! class_exists('ZipArchive', false))
		{
			set_alert(line('CSK_MODULES_ERROR_UPLOAD'), 'error');
			redirect('admin/modules/install');
			exit;
		}

		// Prepare data for later use.
		$data = $this->upload->data();

		$location = ('0' === $this->input->post('location'))
			? APPPATH.'modules/'
			: FCPATH.'content/modules/';

		// Catch the upload status and delete the temporary file anyways.
		$status = unzip_file($data['full_path'], $location);
		@unlink($data['full_path']);
		
		// Successfully installed?
		if (true === $status)
		{
			set_alert(line('CSK_MODULES_SUCCESS_UPLOAD'), 'success');
			redirect('admin/modules');
			exit;
		}

		// Otherwise, the theme could not be installed.
		set_alert(line('CSK_MODULES_ERROR_UPLOAD'), 'error');
		redirect('admin/modules/install');
		exit;
	}

	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------

	/**
	 * Add some plugin language lines to head section.
	 *
	 * @since 	1.3.3
	 *
	 * @access 	public
	 * @param 	string
	 * @return 	string
	 */
	public function _admin_head($output)
	{
		$lines = array(
			'activate'    => line('CSK_MODULES_CONFIRM_ACTIVATE'),
			'deactivate'  => line('CSK_MODULES_CONFIRM_DEACTIVATE'),
			'delete'      => line('CSK_MODULES_CONFIRM_DELETE'),
		);

		$output .= '<script type="text/javascript">';
		$output .= 'csk.i18n = csk.i18n || {};';
		$output .= ' csk.i18n.modules = '.json_encode($lines).';';
		$output .= '</script>';

		return $output;
	}

	// ------------------------------------------------------------------------

	/**
	 * _subhead
	 *
	 * Display admin subhead section.
	 *
	 * @author 	Kader Bouyakoub
	 * @link 	https://goo.gl/wGXHO9
	 * @since 	2.0.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	protected function _subhead()
	{
		if ('install' === $this->router->fetch_method())
		{
			$this->data['page_title'] = line('CSK_MODULES_ADD');

			// Subhead.
			add_action('admin_subhead', function() {

				// Upload module button.
				echo html_tag('button', array(
					'role' => 'button',
					'class' => 'btn btn-primary btn-sm btn-icon mr5',
					'data-toggle' => 'collapse',
					'data-target' => '#module-install'
				), fa_icon('upload').line('CSK_MODULES_UPLOAD'));

				// Back button.
				$this->_btn_back('modules');

			});
		}
		else
		{
			add_action('admin_subhead', function() {
				echo html_tag('a', array(
					'href'  => admin_url('modules/install'),
					'class' => 'btn btn-primary btn-sm btn-icon',
				), fa_icon('upload').line('CSK_MODULES_ADD'));
			});
		}
	}

}
