// handles dynamic reCAPTCHA loading

const loadHandler = "onRecaptchaLoad";
const script = `https://www.google.com/recaptcha/api.js?onload=${loadHandler}`;

export default class ReCaptchaLoader {
	/**
	 * asynchronously injects reCAPTCHA and resolves once fully loaded
	 *
	 * @return {Promise<Object>} the grecaptcha inferface
	 */
	static load () {
		return new Promise((resolve, reject) => {
			if (Reflect.has(self, "grecaptcha")) {
				// we already have it loaded: reset it
				this.instance.reset();
				return resolve(this.instance);
			}

			let el: HTMLScriptElement|null = document.querySelector(`script[src="${script}"]`);

			if (el) {
				// already loading: don't attempt another load
				return;
			} else {
				el = document.createElement("script");
				el.type = "text/javascript";
				el.async = true;
				el.src = script;
			}

			// create a load handler:
			Reflect.set(self, loadHandler, () => {
				resolve(this.instance);
				Reflect.deleteProperty(self, loadHandler);
			});

			// attach the handlers:
			el.addEventListener("error", reject);
			el.addEventListener("abort", reject);
			// no listener for "load": we rely on reCAPTCHA to self-report loading

			// inject the tag and begin loading
			document.head.appendChild(el);
		});
	}

	/**
	 * checks whether reCAPTCHA is ready to use
	 */
	static get isReady () {
		return Reflect.has(self, "grecaptcha");
	}

	static get instance () {
		return this.isReady ? Reflect.get(self, "grecaptcha"): null;
	}
}
