/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/custom.js":
/*!********************************!*\
  !*** ./resources/js/custom.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports) {

eval("// On Load\nwindow.addEventListener(\"load\", function () {\n  // Toggle Sidebar\n  if (localStorage.getItem(\"sidebarOpened\") == \"false\") {\n    document.body.classList.add(\"sidebar-toggled\");\n    document.getElementById(\"accordionSidebar\").classList.add(\"toggled\");\n    localStorage.setItem(\"sidebarOpened\", \"false\");\n  } else {\n    localStorage.setItem(\"sidebarOpened\", \"true\");\n  }\n}); // Window Resize\n\nwindow.addEventListener(\"resize\", function () {\n  if (document.body.clientWidth <= 768) {\n    document.body.classList.add(\"sidebar-toggled\");\n    document.getElementById(\"accordionSidebar\").classList.add(\"toggled\");\n    localStorage.setItem(\"sidebarOpened\", \"false\");\n  } else {\n    document.body.classList.remove(\"sidebar-toggled\");\n    document.getElementById(\"accordionSidebar\").classList.remove(\"toggled\");\n    localStorage.setItem(\"sidebarOpened\", \"true\");\n  }\n}); // Sidebar Toggle Button\n\nvar sidebarToggle = document.getElementById(\"sidebarToggle\");\nsidebarToggle.addEventListener(\"click\", function (e) {\n  var opened = window.getComputedStyle(e.target, \"::after\").getPropertyValue(\"margin-left\") == \"0px\";\n  localStorage.setItem(\"sidebarOpened\", opened);\n}); // Print Element Function\n\nwindow.printElm = function (el) {\n  var restorepage = $(\"body\").html();\n  var printcontent = $(\"#\" + el).clone();\n  $(\"body\").empty().html(printcontent);\n  window.print();\n  $(\"body\").html(restorepage);\n};//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9yZXNvdXJjZXMvanMvY3VzdG9tLmpzP2IxZDIiXSwibmFtZXMiOlsid2luZG93IiwiYWRkRXZlbnRMaXN0ZW5lciIsImxvY2FsU3RvcmFnZSIsImdldEl0ZW0iLCJkb2N1bWVudCIsImJvZHkiLCJjbGFzc0xpc3QiLCJhZGQiLCJnZXRFbGVtZW50QnlJZCIsInNldEl0ZW0iLCJjbGllbnRXaWR0aCIsInJlbW92ZSIsInNpZGViYXJUb2dnbGUiLCJlIiwib3BlbmVkIiwiZ2V0Q29tcHV0ZWRTdHlsZSIsInRhcmdldCIsImdldFByb3BlcnR5VmFsdWUiLCJwcmludEVsbSIsImVsIiwicmVzdG9yZXBhZ2UiLCIkIiwiaHRtbCIsInByaW50Y29udGVudCIsImNsb25lIiwiZW1wdHkiLCJwcmludCJdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQUEsTUFBTSxDQUFDQyxnQkFBUCxDQUF3QixNQUF4QixFQUFnQyxZQUFNO0FBQ2xDO0FBQ0EsTUFBSUMsWUFBWSxDQUFDQyxPQUFiLENBQXFCLGVBQXJCLEtBQXlDLE9BQTdDLEVBQXNEO0FBQ2xEQyxZQUFRLENBQUNDLElBQVQsQ0FBY0MsU0FBZCxDQUF3QkMsR0FBeEIsQ0FBNEIsaUJBQTVCO0FBQ0FILFlBQVEsQ0FBQ0ksY0FBVCxDQUF3QixrQkFBeEIsRUFBNENGLFNBQTVDLENBQXNEQyxHQUF0RCxDQUEwRCxTQUExRDtBQUNBTCxnQkFBWSxDQUFDTyxPQUFiLENBQXFCLGVBQXJCLEVBQXNDLE9BQXRDO0FBQ0gsR0FKRCxNQUlPO0FBQ0hQLGdCQUFZLENBQUNPLE9BQWIsQ0FBcUIsZUFBckIsRUFBc0MsTUFBdEM7QUFDSDtBQUNKLENBVEQsRSxDQVdBOztBQUNBVCxNQUFNLENBQUNDLGdCQUFQLENBQXdCLFFBQXhCLEVBQWtDLFlBQU07QUFDcEMsTUFBSUcsUUFBUSxDQUFDQyxJQUFULENBQWNLLFdBQWQsSUFBNkIsR0FBakMsRUFBc0M7QUFDbENOLFlBQVEsQ0FBQ0MsSUFBVCxDQUFjQyxTQUFkLENBQXdCQyxHQUF4QixDQUE0QixpQkFBNUI7QUFDQUgsWUFBUSxDQUFDSSxjQUFULENBQXdCLGtCQUF4QixFQUE0Q0YsU0FBNUMsQ0FBc0RDLEdBQXRELENBQTBELFNBQTFEO0FBQ0FMLGdCQUFZLENBQUNPLE9BQWIsQ0FBcUIsZUFBckIsRUFBc0MsT0FBdEM7QUFDSCxHQUpELE1BSU87QUFDSEwsWUFBUSxDQUFDQyxJQUFULENBQWNDLFNBQWQsQ0FBd0JLLE1BQXhCLENBQStCLGlCQUEvQjtBQUNBUCxZQUFRLENBQUNJLGNBQVQsQ0FBd0Isa0JBQXhCLEVBQTRDRixTQUE1QyxDQUFzREssTUFBdEQsQ0FBNkQsU0FBN0Q7QUFDQVQsZ0JBQVksQ0FBQ08sT0FBYixDQUFxQixlQUFyQixFQUFzQyxNQUF0QztBQUNIO0FBQ0osQ0FWRCxFLENBWUE7O0FBQ0EsSUFBSUcsYUFBYSxHQUFHUixRQUFRLENBQUNJLGNBQVQsQ0FBd0IsZUFBeEIsQ0FBcEI7QUFFQUksYUFBYSxDQUFDWCxnQkFBZCxDQUErQixPQUEvQixFQUF3QyxVQUFBWSxDQUFDLEVBQUk7QUFDekMsTUFBSUMsTUFBTSxHQUNOZCxNQUFNLENBQ0RlLGdCQURMLENBQ3NCRixDQUFDLENBQUNHLE1BRHhCLEVBQ2dDLFNBRGhDLEVBRUtDLGdCQUZMLENBRXNCLGFBRnRCLEtBRXdDLEtBSDVDO0FBS0FmLGNBQVksQ0FBQ08sT0FBYixDQUFxQixlQUFyQixFQUFzQ0ssTUFBdEM7QUFDSCxDQVBELEUsQ0FTQTs7QUFDQWQsTUFBTSxDQUFDa0IsUUFBUCxHQUFrQixVQUFTQyxFQUFULEVBQWE7QUFDM0IsTUFBSUMsV0FBVyxHQUFHQyxDQUFDLENBQUMsTUFBRCxDQUFELENBQVVDLElBQVYsRUFBbEI7QUFDQSxNQUFJQyxZQUFZLEdBQUdGLENBQUMsQ0FBQyxNQUFNRixFQUFQLENBQUQsQ0FBWUssS0FBWixFQUFuQjtBQUNBSCxHQUFDLENBQUMsTUFBRCxDQUFELENBQ0tJLEtBREwsR0FFS0gsSUFGTCxDQUVVQyxZQUZWO0FBR0F2QixRQUFNLENBQUMwQixLQUFQO0FBQ0FMLEdBQUMsQ0FBQyxNQUFELENBQUQsQ0FBVUMsSUFBVixDQUFlRixXQUFmO0FBQ0gsQ0FSRCIsImZpbGUiOiIuL3Jlc291cmNlcy9qcy9jdXN0b20uanMuanMiLCJzb3VyY2VzQ29udGVudCI6WyIvLyBPbiBMb2FkXG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcihcImxvYWRcIiwgKCkgPT4ge1xuICAgIC8vIFRvZ2dsZSBTaWRlYmFyXG4gICAgaWYgKGxvY2FsU3RvcmFnZS5nZXRJdGVtKFwic2lkZWJhck9wZW5lZFwiKSA9PSBcImZhbHNlXCIpIHtcbiAgICAgICAgZG9jdW1lbnQuYm9keS5jbGFzc0xpc3QuYWRkKFwic2lkZWJhci10b2dnbGVkXCIpO1xuICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImFjY29yZGlvblNpZGViYXJcIikuY2xhc3NMaXN0LmFkZChcInRvZ2dsZWRcIik7XG4gICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKFwic2lkZWJhck9wZW5lZFwiLCBcImZhbHNlXCIpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKFwic2lkZWJhck9wZW5lZFwiLCBcInRydWVcIik7XG4gICAgfVxufSk7XG5cbi8vIFdpbmRvdyBSZXNpemVcbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKFwicmVzaXplXCIsICgpID0+IHtcbiAgICBpZiAoZG9jdW1lbnQuYm9keS5jbGllbnRXaWR0aCA8PSA3NjgpIHtcbiAgICAgICAgZG9jdW1lbnQuYm9keS5jbGFzc0xpc3QuYWRkKFwic2lkZWJhci10b2dnbGVkXCIpO1xuICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZChcImFjY29yZGlvblNpZGViYXJcIikuY2xhc3NMaXN0LmFkZChcInRvZ2dsZWRcIik7XG4gICAgICAgIGxvY2FsU3RvcmFnZS5zZXRJdGVtKFwic2lkZWJhck9wZW5lZFwiLCBcImZhbHNlXCIpO1xuICAgIH0gZWxzZSB7XG4gICAgICAgIGRvY3VtZW50LmJvZHkuY2xhc3NMaXN0LnJlbW92ZShcInNpZGViYXItdG9nZ2xlZFwiKTtcbiAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJhY2NvcmRpb25TaWRlYmFyXCIpLmNsYXNzTGlzdC5yZW1vdmUoXCJ0b2dnbGVkXCIpO1xuICAgICAgICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbShcInNpZGViYXJPcGVuZWRcIiwgXCJ0cnVlXCIpO1xuICAgIH1cbn0pO1xuXG4vLyBTaWRlYmFyIFRvZ2dsZSBCdXR0b25cbmxldCBzaWRlYmFyVG9nZ2xlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoXCJzaWRlYmFyVG9nZ2xlXCIpO1xuXG5zaWRlYmFyVG9nZ2xlLmFkZEV2ZW50TGlzdGVuZXIoXCJjbGlja1wiLCBlID0+IHtcbiAgICBsZXQgb3BlbmVkID1cbiAgICAgICAgd2luZG93XG4gICAgICAgICAgICAuZ2V0Q29tcHV0ZWRTdHlsZShlLnRhcmdldCwgXCI6OmFmdGVyXCIpXG4gICAgICAgICAgICAuZ2V0UHJvcGVydHlWYWx1ZShcIm1hcmdpbi1sZWZ0XCIpID09IFwiMHB4XCI7XG5cbiAgICBsb2NhbFN0b3JhZ2Uuc2V0SXRlbShcInNpZGViYXJPcGVuZWRcIiwgb3BlbmVkKTtcbn0pO1xuXG4vLyBQcmludCBFbGVtZW50IEZ1bmN0aW9uXG53aW5kb3cucHJpbnRFbG0gPSBmdW5jdGlvbihlbCkge1xuICAgIHZhciByZXN0b3JlcGFnZSA9ICQoXCJib2R5XCIpLmh0bWwoKTtcbiAgICB2YXIgcHJpbnRjb250ZW50ID0gJChcIiNcIiArIGVsKS5jbG9uZSgpO1xuICAgICQoXCJib2R5XCIpXG4gICAgICAgIC5lbXB0eSgpXG4gICAgICAgIC5odG1sKHByaW50Y29udGVudCk7XG4gICAgd2luZG93LnByaW50KCk7XG4gICAgJChcImJvZHlcIikuaHRtbChyZXN0b3JlcGFnZSk7XG59O1xuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/custom.js\n");

/***/ }),

/***/ 1:
/*!**************************************!*\
  !*** multi ./resources/js/custom.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\xampp\htdocs\finance-tracker\resources\js\custom.js */"./resources/js/custom.js");


/***/ })

/******/ });