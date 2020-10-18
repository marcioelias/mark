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
/******/ 	return __webpack_require__(__webpack_require__.s = 5);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/insert-text-at-cursor/dist/index.esm.js":
/*!**************************************************************!*\
  !*** ./node_modules/insert-text-at-cursor/dist/index.esm.js ***!
  \**************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var browserSupportsTextareaTextNodes;
/**
 * @param {HTMLElement} input
 * @return {boolean}
 */

function canManipulateViaTextNodes(input) {
  if (input.nodeName !== "TEXTAREA") {
    return false;
  }

  if (typeof browserSupportsTextareaTextNodes === "undefined") {
    var textarea = document.createElement("textarea");
    textarea.value = 1;
    browserSupportsTextareaTextNodes = !!textarea.firstChild;
  }

  return browserSupportsTextareaTextNodes;
}
/**
 * @param {HTMLTextAreaElement|HTMLInputElement} input
 * @param {string} text
 * @returns {void}
 */


function index (input, text) {
  // Most of the used APIs only work with the field selected
  input.focus(); // IE 8-10

  if (document.selection) {
    var ieRange = document.selection.createRange();
    ieRange.text = text; // Move cursor after the inserted text

    ieRange.collapse(false
    /* to the end */
    );
    ieRange.select();
    return;
  } // Webkit + Edge


  var isSuccess = document.execCommand("insertText", false, text);

  if (!isSuccess) {
    var start = input.selectionStart;
    var end = input.selectionEnd; // Firefox (non-standard method)

    if (typeof input.setRangeText === "function") {
      input.setRangeText(text);
    } else {
      // To make a change we just need a Range, not a Selection
      var range = document.createRange();
      var textNode = document.createTextNode(text);

      if (canManipulateViaTextNodes(input)) {
        var node = input.firstChild; // If textarea is empty, just insert the text

        if (!node) {
          input.appendChild(textNode);
        } else {
          // Otherwise we need to find a nodes for start and end
          var offset = 0;
          var startNode = null;
          var endNode = null;

          while (node && (startNode === null || endNode === null)) {
            var nodeLength = node.nodeValue.length; // if start of the selection falls into current node

            if (start >= offset && start <= offset + nodeLength) {
              range.setStart(startNode = node, start - offset);
            } // if end of the selection falls into current node


            if (end >= offset && end <= offset + nodeLength) {
              range.setEnd(endNode = node, end - offset);
            }

            offset += nodeLength;
            node = node.nextSibling;
          } // If there is some text selected, remove it as we should replace it


          if (start !== end) {
            range.deleteContents();
          }
        }
      } // If the node is a textarea and the range doesn't span outside the element
      //
      // Get the commonAncestorContainer of the selected range and test its type
      // If the node is of type `#text` it means that we're still working with text nodes within our textarea element
      // otherwise, if it's of type `#document` for example it means our selection spans outside the textarea.


      if (canManipulateViaTextNodes(input) && range.commonAncestorContainer.nodeName === "#text") {
        // Finally insert a new node. The browser will automatically split start and end nodes into two if necessary
        range.insertNode(textNode);
      } else {
        // If the node is not a textarea or the range spans outside a textarea the only way is to replace the whole value
        var value = input.value;
        input.value = value.slice(0, start) + text + value.slice(end);
      }
    } // Correct the cursor position to be at the end of the insertion


    input.setSelectionRange(start + text.length, start + text.length); // Notify any possible listeners of the change

    var e = document.createEvent("UIEvent");
    e.initEvent("input", true, false);
    input.dispatchEvent(e);
  }
}

/* harmony default export */ __webpack_exports__["default"] = (index);
//# sourceMappingURL=index.esm.js.map


/***/ }),

/***/ 5:
/*!**************************************************!*\
  !*** multi ./node_modules/insert-text-at-cursor ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /mnt/7EDAA7EFDAA7A1BF/Desenvolvimento/Web/mark2/node_modules/insert-text-at-cursor */"./node_modules/insert-text-at-cursor/dist/index.esm.js");


/***/ })

/******/ });