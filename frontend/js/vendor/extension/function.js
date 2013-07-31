Object.extend
(
	Function.prototype,
	{
		applyConstructor: function(params) {
			
			var obj, newobj, ctor = this;

			// Use a fake constructor function with the target constructor's
			// `prototype` property to create the object with the right prototype
			function fakeCtor() {
			}

			fakeCtor.prototype = this.prototype;
			obj = new fakeCtor();

			// Set the object's `constructor`
			obj.constructor = this;

			// Call the constructor function
			newobj = this.apply(obj, params);

			// Use the returned object if there is one.
			// Note that we handle the funky edge case of the `Function` constructor,
			// thanks to Mike's comment below. Double-checked the spec, that should be
			// the lot.
			if (newobj !== null && (typeof newobj === 'object' || typeof newobj === 'function')) {
				obj = newobj;
			}

			// Done
			return obj;
		}
	}
);
