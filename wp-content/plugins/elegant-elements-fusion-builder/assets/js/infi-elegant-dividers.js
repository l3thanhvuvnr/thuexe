( function() {
	"use strict";

	var ElegantDivider = (function() {
		var dpr = window.devicePixelRatio || 1;
		var raf = window.requestAnimationFrame || function(callback) {
			setTimeout(callback, 1000/60);
		};

		// Extra functions for DOM manipulation
		var DomEl = {
			css: function(el, obj, priority) {
				for (var prop in obj) {
					el.style.setProperty(prop, obj[prop], priority || '');
				}
			},
			attr: function(el, obj) {
				for (var prop in obj) {
					el.setAttribute(prop, obj[prop]);
				}
			},
			getInnerEl: function(el, val) {
				if (val == ':after' || val == ':before') {
					return { el: el, pseudo: val };
				} else {
					if (!val || !el) {
						return { el: el };
					}
					var result = el.querySelector(val);
					return { el: result || el };
				}
			},
			getOffset: function(el) {
				var rect = el.getBoundingClientRect(),
				scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
				scrollTop = window.pageYOffset || document.documentElement.scrollTop;
				return {
					top: rect.top + scrollTop,
					left: rect.left + scrollLeft,
					bottom: rect.bottom + scrollTop,
					right: rect.right + scrollTop
				};
			},
			getElementSize: function(el) {
				var elSize = el.getBoundingClientRect();
				return elSize;
			},
			getCssProp: function(el, prop, pseudoEl) {
				if (el.currentStyle && pseudoEl == undefined) {
					return el.currentStyle[prop];
				}
				return window.getComputedStyle(el, pseudoEl).getPropertyValue(prop);
			},
			createDividers: function(size) {
				var canvas = document.createElement('canvas'),
				g = canvas.getContext('2d');

				canvas.width = size.width;
				canvas.height = size.height;
				DomEl.css(canvas, {
					width: size.width + 'px',
					height: size.height + 'px',
				});

				return { canvas: canvas, g: g };
			}
		};

		// Extra functions for CSS parsing
		var Css = {
			// Object for manipulating dynamic <style>
			// Used for :after and :before styles
			Sheet: {
				curId: 0,
				isCreated: false,

				_create: function() {
					var el = document.createElement('style');
					document.head.appendChild(el);
					this.sheet = el.sheet;
					this.isCreated = true;
				},
				add: function(el, pseudo) {
					if (!this.isCreated) {
						this._create();
					}
					var text = '.elegant-divider-' + (this.curId++);
					if (pseudo) {
						text += pseudo;
					}
					text += ' {}';
					return this.sheet.insertRule(text, this.sheet.cssRules.length);
				},
				change: function(id, obj, priority) {
					for (var prop in obj) {
						this.sheet.cssRules[id].style.setProperty(prop, obj[prop], priority || '');
					}
				}
			},
			// Get overlay color and opacity
			getInnerOverlay: function(el, val) {
				if (!val) {
					return undefined;
				}
				el = DomEl.getInnerEl(el, val);

				if (window.getComputedStyle(el.el, el.pseudo)
						.getPropertyValue('display') == 'none') {
					return undefined;
				}

				return {
					color: DomEl.getCssProp(el.el, 'background-color', el.pseudo),
					opacity: parseFloat(DomEl.getCssProp(el.el, 'opacity', el.pseudo)),
				};
			},
			// Parse css value to object
			parseVal: function(val) {
				if (val[0] == 'c') {
					var arr = val.substring(5, val.length-1).split(' ');
					arr = arr.map(function(item) {
						return Css.parseVal(item);
					});
					return {
						rawVal: val,
						type: 'calc',
						val: arr
					};
				}
				switch(val) {
				case 'bottom':
					val = '100%';
					break;
				case 'top':
					val = '0%';
					break;
				case 'left':
					val = '0%';
					break;
				case 'right':
					val = '100%';
					break;
				}
				var r = /-?[\d.]+?([a-z%]+)?($|\s)/g.exec(val);
				if (r) {
					return {
						rawVal: val,
						val: parseFloat(val),
						type: r[1]
					}
				}
				return val;
			},

			// Split string according to quotes by symbol
			splitValues: function(str, sym) {
				var result = [];
				var curVal = '';

				var bracketLevel = 0;
				var quote = false;
				var i = -1;
				while(++i <= str.length) {
					if ((!quote && bracketLevel == 0 && str[i] == sym) || i == str.length) {
						result.push(curVal);
						curVal = '';
					} else {
						if (str[i] == ' ' && curVal == '') {
							continue;
						}
						curVal += str[i];

						if (quote) {
							if (str[i] == '"') {
								quote = false;
							}
							continue;
						}

						switch(str[i]) {
						case '(':
							bracketLevel++;
							break;
						case ')':
							bracketLevel--;
							break;
						case '"':
							quote = true;
							break;
						}
					}
				}

				return result;
			},
			// Parse array of css attributes of background properties
			parseBgProp: function(prop, isPos) {
				var arr = this.splitValues(prop, ',');
				var result = [];

				arr.forEach(function(item) {
					if (item == 'auto' || item == 'cover' || item == 'contain') {
						result.push(item);
						return;
					}
					var values = this.splitValues(item, ' ');
					var x = this.parseVal(values[0]);
					var y = (isPos) ? this.parseVal('0px') : 'auto';
					if (values.length > 1) {
						y = this.parseVal(values[1]);
					}
					result.push({
						x: x, y: y
					});
				}, this);

				return result;
			},

			// Parsing gradient
			gradParser: {
				strAngles: {
					'to left': 270,
					'to right': 90,
					'to bottom': 180,
					'to top': 0,
					'to left top': { x: 1, y: -1 },
					'to left bottom': { x: -1, y: -1 },
					'to right top': { x: 1, y: -1 },
					'to right bottom': { x: -1, y: 1 },
				},
				getAngleFromString: function(str, size) {
					var vec = this.strAngles[str];
					if (typeof vec === 'number') {
						return vec;
					}
					var angle = Math.abs((Math.atan2(vec.x*size.width, vec.y*size.height) / Math.PI*180)-90);
					if (str == 'to left top') {
						angle = 360 - angle;
					}
					return angle;
				},
				// Get angle from css string
				getAngle: function(str) {
					var result = 0;

					if (str.search('deg') >= 0) {
						result = parseInt(str);
					}
					else if (str.search('turn') >= 0) {
						result = parseFloat(str) * 360;
					}

					result = result % 360;
					if (result < 0) {
						result += 360;
					}

					return result;
				},
				// Compute the endpoints so that a gradient of the given angle covers a box of the given size.
				endPointsFromAngle: function(angleDeg, size) {
					var r = {
						first: {},
						second: {}
					};

					if (angleDeg == 90) {
						r.first = { x: 0, y: 0 };
						r.second = { x: size.width, y: 0 };
						return r;
					}
					if (angleDeg == 180) {
						r.first = { x: 0, y: 0 };
						r.second = { x: 0, y: size.height };
						return r;
					}
					if (angleDeg == 270) {
						r.first = { x: size.width, y: 0 };
						r.second = { x: 0, y: 0 };
						return r;
					}

					var slope = Math.tan((90 - angleDeg) * (Math.PI/180));
					var perpendicularSlope = -1 / slope;

					var halfHeight = size.height / 2;
					var halfWidth = size.width / 2;
					var endCorner;
					if (angleDeg < 90) {
						endCorner = { x: halfWidth, y: halfHeight };
					}
					else if (angleDeg < 180) {
						endCorner = { x: halfWidth, y: -halfHeight };
					}
					else if (angleDeg < 270) {
						endCorner = { x: -halfWidth, y: -halfHeight };
					}
					else {
						endCorner = { x: -halfWidth, y: halfHeight };
					}

					var c = endCorner.y - perpendicularSlope * endCorner.x;
					var endX = c / (slope - perpendicularSlope);
					var endY = perpendicularSlope * endX + c;

					r.second = { x: halfWidth + endX, y: halfHeight - endY };
					r.first = { x: halfWidth - endX, y: halfHeight + endY };

					return r;
				},
				parseStop: function(str, size) {
					var r = /(rgba?\(.+?\))\s?(\d+?(%|px))?/g.exec(str);
					var offset = undefined;
					return {
						color: r[1],
						offset: (r[2]) ? Css.parseVal(r[2]) : r[2]
					}
				},
				// Transform all values in percents
				calcStops: function(stops, gradLength) {
					var result = [];

					stops.forEach(function(item) {
						var offset = undefined;
						if (item.offset) {
							switch (item.offset.type) {
							case 'px':
								offset = item.offset.val / gradLength;
								break;
							case '%':
								offset = item.offset.val / 100;
								break;
							}
						}
						result.push({
							color: item.color,
							offset: offset
						})
					});

					return result;
				},
				// Add offset value for empty stops
				normalizeStops: function(stops) {
					var leftOffset = 0;
					var rightOffset = 100;
					var tmpArr = [];

					// Set the start and end value if they are empty
					if (stops[0].offset == undefined) {
						stops[0].offset = 0;
					}
					if (stops[stops.length-1].offset == undefined) {
						stops[stops.length-1].offset = 1;
					}

					stops.forEach(function(item) {
						// Add to temporary array if stop is empty
						if (item.offset == undefined) {
							tmpArr.push(item);
						}
						// Calculate intermediate undefined stops
						else if (tmpArr.length > 0) {
							rightOffset = item.offset;
							tmpArr.forEach(function(item, i) {
								item.offset = (left + (right - left) * (i+1)/(tmpArr.length+1));
							});
							tmpArr = [];
							leftOffset = rightOffset;
						}
						else {
							leftOffset = item.offset;
						}
					});
					return stops;
				},
				parse: function(str, size) {
					var content = /linear-gradient\((.+)\)/gi.exec(str)[1];
					var stops = [];
					var angle = 180;
					var i = -1;
					var curArg = '';
					var bracketLevel = 0;

					// Parse arguments in array
					while(++i <= content.length) {
						if ((bracketLevel == 0 && content[i] == ',') || i == content.length) {
							// If argument is angle
							if (curArg.search('rgb') == -1) {
								angle = this.getAngle(curArg, size);
							}
							else {
								stops.push(this.parseStop(curArg, size));
							}
							curArg = '';
						} else {
							switch(content[i]) {
							case '(':
								bracketLevel++;
								break;
							case ')':
								bracketLevel--;
								break;
							}
							curArg += content[i];
						}
					}

					return {
						angle: angle,
						stops: stops
					};
				}
			},
			_: {}
		};

		var ElegantDivider = {

			// Default settings
			defSettings: {
				type: 'slantLeft',
				elSize: 100,
				mobileHeight: 60,
				responsiveBreakPoint: 768,
				offset: 0,
				zIndex: 2,
				draw: 'both',
				inside: 'none',
				dynamic: false
			},

			// Managment elements connected by canvas
			Element: {
				arr: [],

				refresh: function() {
					this.arr.forEach(function(element) {
						element.el.elegantRefresh();
					});
				},

				create: function(element, canvas) {
					var el = element.el;
					el.elegantElSize = DomEl.getElementSize(el);
					el.elegantBgInfo = {};
					el.elegantCompStyles = {};
					el.elegantVertArr = [canvas];

					// Create css rule in <style> if element used pseudo element
					if (element.pseudo) {
						el.elegantSheetId = Css.Sheet.add(el, element.pseudo);
						el.className += ' elegant-divider-' + el.elegantSheetId;
					}

					// Resize observer
					if ( ElegantDivider.observer ) {
						ElegantDivider.observer.observe(el, {});
					}

					// Get additional sizes after the influence of canvas
					el.elegantGetPadding = function() {
						var elementOffset = DomEl.getOffset(el);
						var elementSize = DomEl.getElementSize(el);
						var w = 0, h = 0;

						this.elegantVertArr.forEach(function(item) {
							var canvasOffset = DomEl.getOffset(item);
							var canvasSize = DomEl.getElementSize(item);

							if (canvasOffset.top > elementOffset.top) {
								h -= (elementOffset.top + elementSize.height)
									- (canvasOffset.top + canvasSize.height);
							} else {
								h += elementOffset.top - canvasOffset.top;
							}
						});

						return {
							width: w,
							height: h
						};
					};

					// Get background offset after the influence of canvas
					el.elegantGetBgOffset = function() {
						var x = 0, y = 0;

						this.elegantVertArr.forEach(function(item) {
							var diffY = DomEl.getOffset(item).top - DomEl.getOffset(this).top;
							if (diffY < 0 && diffY < y) {
								y = diffY;
							};
						}, this);

						return {
							x: x,
							y: y
						};
					};
					// Refresh sizes & element background properties
					el.elegantRefresh = function() {
						this.elegantElSize = DomEl.getElementSize(this);
						var info = this.elegantBgInfo = el.elegantRefreshBgInfo();

						// Refresh sizes
						this.elegantPadding = this.elegantGetPadding();
						this.elegantBgOffset = this.elegantGetBgOffset();

						// Refresh css background properties
						var bgSize = [];
						var bgPos = [];
						this.elegantSize = {
							width: this.elegantElSize.width + this.elegantPadding.width,
							height: this.elegantElSize.height + this.elegantPadding.height,
							x: 0, y: 0
						};
					};

					// Get and parse background computed styels
					el.elegantRefreshBgInfo = function() {
						var self = this;
						var ctCS = this.elegantCompStyles;
						var info = this.elegantBgInfo;
						var elementSize = DomEl.getElementSize(this);

						// Get computed styles
						var cs = {
							fill: DomEl.getCssProp(this, 'background-color', element.pseudo)
						};

						// Fill
						if (ctCS.fill != cs.fill) {
							info.fill = cs.fill;
						}

						// Size
						if (ctCS.elSize != cs.elSize) {
							info.rawSize = Css.splitValues(cs.elSize, ',');
							info.elSize = Css.parseBgProp(cs.elSize);
							// If IE returned a pixel value equal to the height, then set it to 100%
							if (document.documentMode) {
								info.elSize.forEach(function(el) {
									if (el.y && el.y.type == 'px' && el.y.val == parseInt(self.elegantElSize.height)) {
										el.y.type = '%';
										el.y.val = 100;
									}
								});
							}
						}
						// Position
						if (ctCS.pos != cs.pos) {
							info.pos = Css.parseBgProp(cs.pos, true);
						}
						// Repeat
						if (ctCS.repeat != cs.repeat) {
							info.repeat = Css.splitValues(cs.repeat, ',');
						}

						// Refresh computed styles
						this.elegantCompStyles = cs;

						return info;
					};
				},

				// Push unique element or add to existing one
				push: function(element, canvas) {
					var hasItem = true;
					this.arr.forEach(function(item) {
						if (item.el == element.el) {
							element.el.elegantVertArr.push(canvas);
							hasItem = false;
						}
					});
					if (hasItem) {
						this.create(element, canvas);
						this.arr.push(element);
					}
				}
			},

			// Managment <canvas>
			Dividers: {
				arr: [],

				// Gradient data to canvas gradient
				createLGrad: function(grad, offset, bgSize, g) {
					var angle = grad.angle;
					if (typeof angle === 'string') {
						angle = Css.gradParser.getAngleFromString(angle, bgSize);
					}
					var p = Css.gradParser.endPointsFromAngle(angle, bgSize);

					var result = g.createLinearGradient(
						p.first.x + offset.x || 0,
						p.first.y + offset.y || 0,
						p.second.x + offset.x || 0,
						p.second.y + offset.y || 0
					);

					// Add stops
					var gradLength = Math.sqrt(
						Math.pow(p.first.x - p.second.x, 2) +
						Math.pow(p.first.y - p.second.y, 2)
					);
					// Transform all values to percent
					var stops = Css.gradParser.calcStops(grad.stops, gradLength);
					// Fill spaces
					Css.gradParser.normalizeStops(stops);
					// Set up stops
					stops.forEach(function(item) {
						result.addColorStop(item.offset, item.color);
					});

					return result;
				},

				// Creeate canvas for pattern
				createPattern: function(el, options) {
					var canvas = document.createElement('canvas');
					var g = canvas.getContext('2d');

					return {
						el: el,
						canvas: canvas,
						g: g,
					};
				},
				// Draw element background on pattern canvas
				refreshPattern: function(pattern, canvas, overlay, isPrev) {
					var bgInfo = pattern.el.elegantBgInfo;
					var elementSize = pattern.el.elegantSize;
					var size = canvas.elSize;
					var g = pattern.g;

					DomEl.attr(pattern.canvas, size);

					g.clearRect(0, 0, size.width, size.height);

					// Draw rect with element background-color
					g.fillStyle = bgInfo.fill;
					g.fillRect(0, 0, size.width, size.height);

					// Draw overlay
					if (overlay) {
						g.globalAlpha = overlay.opacity;
						g.fillStyle = overlay.color;
						g.fillRect(0, 0, size.width, size.height);
						g.globalAlpha = 1;
					}

					if (pattern.canvas.width > 0) {
						pattern.pattern = canvas.g.createPattern(pattern.canvas, 'repeat');
					}
				},

				// Refresh sizes of all shapes in divider
				refreshShapes: function(div) {
					var size = div.canvas.elSize;
					var shapes = ElegantDivider.divs.get(div.type, size.width, size.height).shapes;

					shapes.forEach(function(shape, i) {
						div.shapes[i] = shape;
						shape.curPath = shape.d;
						shape.animCur = 0;
						shape.animStartTime = new Date().getTime();
					});
				},

				createDivider: function(result) {
					var size = result.canvas.elSize;
					// Create patterns
					result.prevPattern = DomEl.createDividers(size);
					result.prevPattern.el = result.prevEl;
					result.nextPattern = DomEl.createDividers(size);
					result.nextPattern.el = result.nextEl;

					var custom = ElegantDivider.divs.arr[result.type].custom;
					if (custom) {
						result.custom = {
							draw: custom.draw,
							resize: custom.resize
						};
					}

					result.shapes = [];
					this.refreshDivider(result);
					return result;
				},
				refreshDivider: function(div) {
					var c = div.canvas;
					this.refreshPattern(div.prevPattern, c, c.prevOverlay);
					this.refreshPattern(div.nextPattern, c, c.nextOverlay, true);

					if (c.elSizeIsChanged) {
						if (div.custom) {
							div.custom.animStartTime = new Date().getTime();
						} else {
							this.refreshShapes(div);
						}
					}
					this.drawDivider(div, 1, true);
				},

				// Draw a divider using patterns
				drawDivider: function(divs, animCurTime, isResize) {
					var g = divs.canvas.g;
					var el = divs.canvas.canvas;
					var size = divs.canvas.elSize;
					var draw = divs.canvas.settings.draw;

					g.clearRect(0, 0, size.width, size.height);

					// Draw first pattern on all area
					g.fillStyle = divs.prevPattern.pattern;
					g.fillRect(0, 0, size.width, size.height);
					g.save();

					// Draw second pattern
					g.fillStyle = divs.nextPattern.pattern || '#000';

					if (divs.custom) {
						if (isResize && size.width > 0) {
							divs.custom.resize(el, g, size, g.fillStyle);
						}
						divs.custom.draw(el, g, size, g.fillStyle, animCurTime || 0);
						g.restore();
						return;
					}

					divs.shapes.forEach(function(shape) {
						var fPath = shape.curPath;
						g.globalAlpha = shape.opacity || 1;
						g.beginPath();

						g.scale(size.width, size.height);
						for (var i = 0; i < fPath.length; i++) {
							if (typeof fPath[i] == 'number') {
								g.lineTo(fPath[i], fPath[++i]);
								continue;
							}
							switch(fPath[i]) {
							case 'M':
								g.moveTo(fPath[++i], fPath[++i]);
								break;
							case 'L':
								g.lineTo(fPath[++i], fPath[++i]);
								break;
							case 'A':
								g.scale(1/size.width, 1/size.height);
								g.arc(
									fPath[++i] * size.width, fPath[++i] * size.height,
									Math.abs(fPath[++i])*size.height,
									fPath[++i], fPath[++i]
								);
								g.scale(size.width, size.height);
								break;
							case 'Q':
								g.quadraticCurveTo(
									fPath[++i], fPath[++i],
									fPath[++i], fPath[++i]
								);
								break;
							case 'C':
								g.bezierCurveTo(
									fPath[++i], fPath[++i],
									fPath[++i], fPath[++i],
									fPath[++i], fPath[++i]
								);
								break;
							}
						}
						g.scale(1/size.width, 1/size.height);
						g.fill();
					});

					g.restore();
				},

				// Refresh sizes & offsets for all dividers
				refresh: function() {
					this.arr.forEach(function(divider) {
						divider.refreshDividers();
					});
				},

				// Redraw all dividers
				redraw: function() {
					this.arr.forEach(function(divider) {
						divider.redraw();
					});
				},

				// Create all not created dividers
				build: function() {
					this.arr.forEach(function(item) {
						item.create();
					});
				},

				// Create object for controlling <canvas>
				create: function(el) {
					// Update sizes & offset of <canvas>
					this.refreshDividers = function() {
						DomEl.css(this.canvas, {
							position: 'absolute',
							top: '2px',
							opacity: '0'
						});

						// Get coordinates from any involved elements
						var anyElements = (this.prevEl.el) ? this.prevEl.el : this.nextEl.el;
						var elementSize = DomEl.getElementSize(anyElements);
						var elementOffset = DomEl.getOffset(anyElements);

						// Process size
						var size = this.settings.elSize;
						if ( window.innerWidth <= this.settings.responsiveBreakPoint ) {
							size = this.settings.mobileHeight;
						}

						jQuery( this.prevEl.el ).parent( '.elegant-animated-divider' ).css( 'height', size + 'px' );

						DomEl.css(this.canvas, {
							width: elementSize.width + 'px',
							height: size + 'px',
						});

						// Refresh size
						this.elSize = DomEl.getElementSize(this.canvas);
						this.elSizeIsChanged = this.elSize.width != this._oldSize.width ||
						this.elSize.height != this._oldSize.height;
						this._oldSize = this.elSize;

						// Get offsets
						var canvasOffset = DomEl.getOffset(this.canvas);
						var divOffset = ElegantDivider.divs.arr[this.settings.type].offset;

						// Calculate margin
						// Get left offset from any elements
						var marginLeft = canvasOffset.left - elementOffset.left;
						var marginTop = canvasOffset.top;

						// Put canvas in the middle
						var prevOffset = DomEl.getOffset(this.prevEl.el).bottom;
						var nextOffset = DomEl.getOffset(this.nextEl.el).top;
						marginTop -= prevOffset + ((nextOffset - prevOffset) / 2);
						marginTop += size/2;

						// Add additive margin from user settings
						marginTop -= this.settings.offset / 100 * size;

						// Add additive margin from divider type settings
						marginTop -= divOffset * size;

						// Set css
						marginLeft = ( window.outerWidth - this.elSize.width ) / 2;
						DomEl.css(this.canvas, {
							top: '2px',
							left: -marginLeft + 'px',
							opacity: 1,
							'z-index': this.settings.zIndex +'',
						});

						// Refresh canvas size
						DomEl.css(this.canvas, {
							width: '',
							height: ''
						});

						this.canvas.width = this.elSize.width * dpr;
						this.canvas.height = this.elSize.height * dpr;
						this.g.scale(dpr, dpr);

						if ( window.innerWidth <= this.settings.responsiveBreakPoint ) {
							DomEl.css(this.canvas, {
								width: window.innerWidth + 'px',
								height: this.elSize.height + 'px',
							});
						} else {
							DomEl.css(this.canvas, {
								width: window.outerWidth + 'px',
								height: this.elSize.height + 'px',
							});
						}
					};

					this.create = function() {
						if (!this.created) {
							// Create array of objects for drawing divider
							this.div = ElegantDivider.Dividers.createDivider({
								canvas: this,
								prevEl: this.prevEl.el,
								nextEl: this.nextEl.el,
								type: this.settings.type
							});

							this.created = true;
						}
					};

					this.redraw = function() {
						ElegantDivider.Dividers.refreshDivider(this.div);
					};

					this.parseSetting = function(attr, set, type) {
						var val = el.getAttribute('data-elegant-divider' + attr);

						if (val != undefined && val !== '') {
							switch(type) {
							case 'number':
								val = parseFloat(val);
								break;
							case 'boolean':
								val = val == 'true';
								break;
							}
						} else {
							val = ElegantDivider.settings[set];
						}

						return val;
					};

					// Get settings
					this.settings = {
						type: this.parseSetting('', 'type'),
						elSize: this.parseSetting('-height', 'elSize', 'number'),
						mobileHeight: this.parseSetting('-mobile-height', 'mobileHeight', 'number'),
						responsiveBreakPoint: this.parseSetting('-responsive-break-point', 'responsiveBreakPoint', 'number'),
						offset: this.parseSetting('-offset', 'offset', 'number'),
						draw: this.parseSetting('-draw', 'draw'),
						zIndex: this.parseSetting('-zindex', 'zIndex', 'number'),
						prevOverlay: this.parseSetting('-prev-overlay', 'overlay'),
						nextOverlay: this.parseSetting('-next-overlay', 'overlay'),
						nextBg: this.parseSetting('-next-bg', 'innerBg'),
						prevBg: this.parseSetting('-prev-bg', 'innerBg'),
						inside: this.parseSetting('-inside', 'inside')
					};

					// Check if type exists
					if (!ElegantDivider.divs.arr[this.settings.type]) {
						console.log('Elegant Animated Divider Type `'+this.settings.type+'` not found!');
						return;
					}

					// Create <canvas> and replace
					this.canvas = document.createElement('canvas');
					this.g = this.canvas.getContext('2d');
					el.parentNode.replaceChild(this.canvas, el);

					// Sets properties for access from <canvas>
					this.canvas.elegantDividers = this;
					this.canvas.elegantRefreshElement = (function() {
						if (this.prevEl.el && this.prevEl.el.elegantRefresh) {
							this.prevEl.el.elegantRefresh();
						}
						if (this.nextEl.el && this.nextEl.el.elegantRefresh) {
							this.nextEl.el.elegantRefresh();
						}
					}).bind(this);
					this.canvas.elegantRefresh = (function() {
						this.refreshDividers();
						this.canvas.elegantRefreshElement();
						this.redraw();
					}).bind(this);

					// Copy attributes from old element to <canvas>
					for (var i = 0; i < el.attributes.length; i++) {
						var attr = el.attributes[i];
						if (attr.name.indexOf('data-elegant-divider') != -1) {
							continue;
						}
						this.canvas.setAttribute(attr.name, attr.value);
					}

					var prev = this.canvas.previousElementSibling;
					var next = this.canvas.nextElementSibling;

					// Prcoess `inside` setting
					switch (this.settings.inside) {
					case 'prev':
						prev = this.canvas.parentNode;
						next = prev.nextElementSibling;
						break;
					case 'next':
						next = this.canvas.parentNode;
						prev = next.previousElementSibling;
						break;
					default:
						// Place the divider inside one of the elements so that it follows its position
						var element = (next) ? next : prev;
						element.appendChild(this.canvas);
						if ( DomEl.getCssProp(element, 'position') == 'static' ) {
							DomEl.css(element, { position: 'relative' });
						}
						break;
					}

					// Get previous and next elements
					this.prevEl = DomEl.getInnerEl(prev, this.settings.prevBg);
					this.nextEl = DomEl.getInnerEl(next, this.settings.nextBg);

					// Push sibling elements for process
					ElegantDivider.Element.push(this.prevEl, this.canvas);
					this.prevOverlay = Css.getInnerOverlay(
						this.prevEl.el,
						this.settings.prevOverlay
					);

					ElegantDivider.Element.push(this.nextEl, this.canvas);
					this.nextOverlay = Css.getInnerOverlay(
						this.nextEl.el,
						this.settings.nextOverlay
					);

					// Create necessary variables
					this._oldSize = { width: -1, height: -1 };

					this.refreshDividers();

					ElegantDivider.Dividers.arr.push(this);
					return this;
				},
			},

			// Manipulation of coordinates
			coords: {
				pattern: function(x, y, s, arr) {
					return function(w, h) {
						var size = s * h;
						var count = Math.ceil(w / size) || 1;

						size = 1 / count;
						var px = x * size;

						var result = ['M', px, y+1];

						for (var i = -1; i < count+1; i++) {
							if (typeof arr === 'function') {
								arr(result, px + i * size, y, w, h, size, i);
							} else {
								ElegantDivider.coords.resize(px + i * size, y, size, 1, arr, result);
							}
						}
						result.push(px + 1 + size, y + 1);
						return result;
					};
				},
				resize: function(x, y, w, h, arr, result) {
					var result = result || [];
					for (var i = 0; i < arr.length;) {
						if (typeof arr[i] == 'string') {
							if (arr[i] == 'A') {
								result.push(
									arr[i++], x + arr[i++]*w, y + arr[i++]*h,
									arr[i++]*h, arr[i++], arr[i++]
								);
							} else {
								result.push(arr[i++]);
							}
							continue;
						}
						result.push(x + arr[i++]*w, y + arr[i++]*h);
					}
					return result;
				},
				interpolate: function(arr, arr2, t) {
					var result = [];
					arr.forEach(function(item, i) {
						if (typeof item == 'string') {
							result.push(item);
						} else {
							result.push(item + (arr2[i] - item) * t);
						}
					});
					return result;
				}
			},

			// Shapes generator
			generator: {
				triangles: function(x, y, s, radius, anim) {
					var dev = (anim) ? (.1 + Math.random()*.1) : 0;
					if (radius) {
						return ElegantDivider.coords.pattern(x, y, s, function(result, x, y, w, h, s) {
							var rx = radius*s;
							var ry = radius*1.5;

							result.push(
								x-rx, y+1-ry-dev,
								'Q', x, y+1-dev,
								x+rx, y+1-ry-dev,
								x-rx+s/2, y+ry+dev,
								'Q', x+s/2, y+dev,
								x+rx+s/2, y+ry+dev
							);
						});
					}
					return ElegantDivider.coords.pattern(x, y, s, [0, 1-dev, .5, dev]);
				},
				waves: function(x, y, s, anim) {
					var dev = (anim) ? .4 : 0;
					return ElegantDivider.coords.pattern(x, y, s, [
						'Q', .25, -.5+dev, .5, .5,
						'Q', .75, 1.5-dev, 1, .5
					]);
				},
				circles: function(x, y, s, r, cloud, rand) {
					return ElegantDivider.coords.pattern(x, y, s, function(result, x, y, w, h, s, i) {
						var rad = r;
						if (cloud) {
							rad *= (i%4 > 2) ? (i%4)/4 : (4-(i%4))/4;
						}
						if (rand) {
							y += (Math.random()*6 * ((i%2)+1))/h;
						}
						result.push(
							'M', x, y+1,
							'A', x, y+1, rad, -Math.PI, Math.PI
						);
					});
				},
				_: {}
			},

			// Dividers store
			divs: {
				// Store divs data
				arr: [],

				// Get divider info with caculated sizes
				get: function(name, w, h) {
					var div = this.arr[name];

					var result = {
						offset: div.offset,
						shapes: []
					};

					div.shapes.forEach(function(shape) {
						var item = {
							opacity: shape.opacity,
							animEasing: shape.animEasing,
							d: (typeof shape.d == 'function') ? shape.d(w, h) : shape.d
						};

						if (shape.animKeys) {
							item.animKeys = [];

							shape.animKeys.forEach(function(key) {
								item.animKeys.push({
									duration: key.duration,
									d: (typeof key.d == 'function') ? key.d(w, h) : key.d
								});
							});
						}

						result.shapes.push(item);
					});

					return result;
				}
			},

			// Interpolate easing functions
			easing: {
				quadraticEaseInOut: function(t) {
					if (t < 0.5) {
						return 2 * t * t;
					} else {
						return (-2 * t * t) + (4 * t) - 1;
					}
				}
			},

			// Animate shapes.
			processAnimation: function() {
				var curTime = new Date().getTime();

				// Check resize if observer is not supported
				if (!this.observer) {
					for (var i = 0; i < this.Element.arr.length; i++) {
						var el = this.Element.arr[i].el;
						var size = el.getBoundingClientRect();

						if (Math.round(size.width) != Math.round(el.elegantElSize.width) || Math.round(size.height) != Math.round(el.elegantElSize.height) ) {
							var dividers = el.elegantVertArr;

							for (var j = 0; j < dividers.length; j++) {
								dividers[j].elegantDividers.refreshDividers();
							}
							el.elegantRefresh();
							for (var j = 0; j < dividers.length; j++) {
								dividers[j].elegantDividers.redraw();
							}
						}
					}
				}

				this.Dividers.arr.forEach(function(canvas) {
					var div = canvas.div;

					div.shapes.forEach(function(shape) {
						var staticPath = shape.d;
						var anim = shape.animKeys;

						if (anim) {
							var cur = shape.animCur;
							var first = (cur > 0) ? anim[cur-1].d : staticPath;
							var second = (cur == anim.length) ? staticPath : anim[cur].d;
							var duration = 2000;
							if (cur != anim.length) {
								duration = anim[cur].duration || duration;
							}
							// Get time diff percent
							var timeDiff = curTime - shape.animStartTime;
							var percent = timeDiff / duration;
							// Easing
							var easing = this.easing.quadraticEaseInOut;

							shape.curPath = this.coords.interpolate(
								first, second,
								easing((percent >= 1) ? 1 : percent)
							);
							ElegantDivider.Dividers.drawDivider(div);

							// Check next key
							if (timeDiff >= duration) {
								shape.animCur++;
								shape.animStartTime = curTime;
								if (shape.animCur > anim.length) {
									shape.animCur = 0;
								}
							}
						}
					}, this);

				}, this);

				raf(this.processAnimation.bind(this));
			},

			// Library initialization
			init: function(settings) {
				var self = this;
				this.settings = settings || {};

				for (var attr in this.defSettings) {
					if (this.settings[attr] === undefined) {
						this.settings[attr] = this.defSettings[attr];
					}
				}

				if (window.ResizeObserver) {
					this.observer = new ResizeObserver(function(entries){
						for (var i = 0; i < entries.length; i++) {
							var dividers = entries[i].target.elegantVertArr;
							for (var j = 0; j < dividers.length; j++) {
								dividers[j].elegantDividers.refreshDividers();
							}
							entries[i].target.elegantRefresh();
							for (var j = 0; j < dividers.length; j++) {
								dividers[j].elegantDividers.redraw();
							}
						}
					});
				}

				this.update();
				raf(this.processAnimation.bind(this));
			},

			add: function(name, offset, shapes) {
				var result = { offset: offset };

				if (Array.isArray(shapes)) {
					result.shapes = shapes;
				} else {
					result.custom = shapes;
				}

				this.divs.arr[name] = result;
			},

			// Refresh sizes
			refresh: function() {
				this.Dividers.refresh();
				this.Element.refresh();
				this.Dividers.redraw();
			},

			// Turn [data-elegant-divider] to dividers
			update: function() {
				var dividerElements = document.querySelectorAll('[data-elegant-divider]');

				// Create <canvas> & refresh sizes and offsets
				var dividers = [];
				for (var i = 0; i < dividerElements.length; i++) {
					this.settings.elSize = 100;
					dividers.push(new this.Dividers.create(dividerElements[i]));
				}

				// Update elements
				for (var i = 0; i < dividers.length; i++) {
					dividers[i].canvas.elegantRefreshElement();
				}

				// Build dividers
				this.Dividers.build();
			}
		};

		// Append divs
		var g = ElegantDivider.generator;
		var divsArr = ElegantDivider.divs.arr;

		// Add Shapes.
		ElegantDivider.add('slantRight', 0, [{
			d: ['M', 0,1, 0,0, 1,1, 1,1],
			animKeys: [{
				d: ['M', 0,1, 0,.25, 1,.75, 1,1]
			}]
		}]);
		ElegantDivider.add('slantLeft', 0, [{
			d: ['M', 0,1, 0,1, 1,0, 1,1],
			animKeys: [{
				d: ['M', 0,1, 0,.75, 1,.25, 1,1]
			}]
		}]);
		ElegantDivider.add('triangleTop', 0, [{
			d: ['M', 0,1, .5,0, 1,1],
			animKeys: [{
				d: ['M', 0,1, .5,.25, 1,1]
			}]
		}]);
		ElegantDivider.add('triangleBottom', 0, [{
			d: ['M', 0,1, 0,0, .5,1, 1,0, 1,1],
			animKeys: [{
				d: ['M', 0,1, 0,0, .5,.75, 1,0, 1,1]
			}]
		}]);
		ElegantDivider.add('curveTop', .1, [{
			d: ['M', 0,1, 'Q', .5,-1, 1,1],
			animKeys: [{
				d: ['M', 0,1, 'Q', .5,-.75, 1,1]
			}]
		}]);
		ElegantDivider.add('curveBottom', -.2, [{
			d: ['M', 0,1, 0,0, 'Q', .5,2, 1,0, 1,1 ],
			animKeys: [{
				d: ['M', 0,1, 0,0, 'Q', .5,1.75, 1,0, 1,1 ]
			}]
		}]);
		ElegantDivider.add('curveLeft', -0.5, [{
			d: ['M', 0,1, 0,0, 'C', .1,1, .2,1, 1,1 ],
			animKeys: [{
				d: ['M', 0,1, 0,0, 'C', .2,1, .3,1, 1,1 ],
			}]
		}]);
		ElegantDivider.add('curveRight', -0.5, [{
			d: ['M', 0,1, 'C', .8,1, .9,1, 1,0, 'L', 1,1 ],
			animKeys: [{
				d: ['M', 0,1, 'C', .7,1, .8,1, 1,0, 'L', 1,1 ],
			}]
		}]);
		ElegantDivider.add('waveLeft', 0, [{
			d: ['M', 0,1, 0,1, 'C', .33,-1.5, .66,2.5, 1,0, 1,1],
			animKeys: [{
				d: ['M', 0,1, 0,1, 'C', .2,-1, .8,2, 1,0, 1,1],
			}]
		}]);
		ElegantDivider.add('waveRight', 0, [{
			d: ['M', 0,1, 0,0, 'C', .33,2.5, .66,-1.5, 1,1, 1,1],
			animKeys: [{
				d: ['M', 0,1, 0,0, 'C', .33,2, .66,-1, 1,1, 1,1],
			}]
		}]);
		ElegantDivider.add('waves', 0, [{
			d: g.waves(-0.5, 0, 5),
			animKeys: [{
				d: g.waves(-0.5, 0, 5, true),
			}]
		}]);
		ElegantDivider.add('hills', 0, [{
			opacity: .6,
			d: g.triangles(0, 0, 3, 0),
			animKeys: [{
				d: g.triangles(0, 0, 3, 0, true)
			}]
		}, {
			d: g.triangles(-0.5, 0, 3, 0),
			animKeys: [{
				d: g.triangles(-0.5, 0, 3, 0, true)
			}]
		}]);
		ElegantDivider.add('hillsRounded', 0, [{
			opacity: .6,
			d: g.triangles(0, 0, 3, .05),
			animKeys: [{
				d: g.triangles(0, 0, 3, .05, true)
			}]
		},{
			d: g.triangles(-0.5, 0, 3, .05),
			animKeys: [{
				d: g.triangles(-0.5, 0, 3, .05, true)
			}]
		}]);
		ElegantDivider.add('wavesOpacity', 0, [{
			opacity: .6,
			d: g.waves(0, 0, 5),
			animKeys: [{
				d: g.waves(0, 0, 5, true),
			}]
		}, divsArr['waves'].shapes[0]]);
		ElegantDivider.add('slantLeftOpacity', 0, [{
			opacity: .4,
			d: ['M', 0,1, 0,.6, 1,0, 1,1],
			animKeys: [{
				d: ['M', 0,1, 0,.4, 1,0, 1,1]
			}]
		},{
			opacity: .6,
			d: ['M', 0,1, 0,.8, 1,.2, 1,1],
		},{
			d: ['M', 0,1, 1,.4, 1,1, 1,1],
			animKeys: [{
				d: ['M', 0,1, 1,.3, 1,.9, 1,1]
			}]
		}]);
		ElegantDivider.add('slantRightOpacity', 0, [{
			opacity: .4,
			d: ['M', 0,1, 0,0, 1,.6,1,1],
			animKeys: [{
				d: ['M', 0,1, 0,0, 1,.4, 1,1]
			}]
		},{
			opacity: .6,
			d: ['M', 0,1, 0,.2, 1,.8, 1,1],
		},{
			d: ['M', 0,1, 0,.4, 1,1, 1,1],
			animKeys: [{
				d: ['M', 0,1, 0,.6, 1,1, 1,1],
			}]
		}]);
		ElegantDivider.add('cloudsLarge', 0, [{
			opacity: 0.6,
			d: g.circles(0.5, 0, 1.5, 0.95),
			animKeys: [{
				d: g.circles(0.5, 0, 1.5, 0.95, false, true),
			}]
		},{
			d: g.circles(0, 0.3, 1.5, 0.9),
			animKeys: [{
				d: g.circles(0, 0.3, 1.5, 0.9, false, true),
			}]
		}]);

		// Creates a new divider from the existing two
		var mergeTrans = function(name, first, second) {
			ElegantDivider.add(name, 0, [{
				opacity: .6,
				d: divsArr[first].shapes[0].d,
				animKeys: divsArr[first].shapes[0].animKeys
			}, divsArr[second].shapes[0] ]);
		};

		mergeTrans('slantRightBg', 'slantLeft', 'slantRight');
		mergeTrans('slantLeftBg', 'slantRight', 'slantLeft');
		mergeTrans('triangleTopBg', 'triangleBottom', 'triangleTop');
		mergeTrans('slantBothBg', 'slantRight', 'slantRightBg');
		mergeTrans('triangleBottomBg', 'triangleTop', 'triangleBottom');
		mergeTrans('curveTopBg', 'curveBottom', 'curveTop');
		mergeTrans('curveBottomBg', 'curveTop', 'curveBottom');
		mergeTrans('waveLeftBg', 'waveRight', 'waveLeft');
		mergeTrans('waveRightBg', 'waveLeft', 'waveRight');
		mergeTrans('curveLeftBg', 'curveLeft', 'curveRight');
		mergeTrans('curveRightBg', 'curveRight', 'curveLeft');

		return ElegantDivider;
	})();

	jQuery( document ).ready( function() {
		var elegantDividers = ElegantDivider;
		elegantDividers.init();
	} );

	jQuery( document ).on( 'fusion-element-render-iee_animated_dividers', function( event, cid ) {
		var elegantDividers = ElegantDivider;
		elegantDividers.init();
	} );

}( jQuery ) );
