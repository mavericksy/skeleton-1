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
 * @since 		1.0.0
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Controller
 *
 * @package 	CodeIgniter
 * @subpackage 	Skeleton
 * @category 	Controllers
 * @author 		Kader Bouyakoub <bkader@mail.com>
 * @link 		https://goo.gl/wGXHO9
 * @copyright 	Copyright (c) 2018, Kader Bouyakoub (https://goo.gl/wGXHO9)
 * @since 		1.0.0
 * @version 	2.1.0
 */
class Admin extends Admin_Controller
{
	/**
	 * Main admin panel page.
	 * @access 	public
	 * @return 	void
	 */
	public function index()
	{
		// EDIT THIS METHOD TO SUIT YOUR NEEDS.
		add_action('admin_index_stats', array($this, '_stats'), 0);

		// Set page title and render view.
		$this->theme
			->set_title(line('CSK_ADMIN_ADMIN_PANEL'))
			->render($this->data);
	}

	// ------------------------------------------------------------------------

	/**
	 * Collect all regular status.
	 *
	 * @since 	2.1.0
	 *
	 * @access 	public
	 * @param 	none
	 * @return 	void
	 */
	public function _stats()
	{
		$output = '<div class="col-xs-6 col-sm-6 col-md-3">';

		// Users count.
		$boxes[] = info_box(
			$this->kbcore->users->count(),
			line('CSK_ADMIN_USERS'),
			'users',
			admin_url('users'),
			'green'
		);

		// Themes count.
		$boxes[] = info_box(
			count($this->theme->get_themes()),
			line('CSK_ADMIN_THEMES'),
			'paint-brush',
			admin_url('themes'),
			'orange'
		);

		// Plugins count.
		$boxes[] = info_box(
			count($this->theme->get_themes()),
			line('CSK_ADMIN_PLUGINS'),
			'plug',
			admin_url('plugins'),
			'red'
		);

		// Languages count.
		$boxes[] = info_box(
			count($this->config->item('languages')),
			line('CSK_ADMIN_LANGUAGES'),
			'globe',
			admin_url('languages'),
			'teal'
		);

		$output .= implode('</div><div class="col-xs-6 col-sm-6 col-md-3">', $boxes);

		$output .= '</div>';
		echo $output;
	}

}
