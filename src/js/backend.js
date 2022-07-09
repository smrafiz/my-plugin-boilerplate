/**
 * Backend JS.
 *
 */

'use strict';

import '../scss/backend.scss';
import { fields } from './backend/fields';

let admin = {};

admin = {
	init: () => {
		fields();
	},
};

admin.init();
