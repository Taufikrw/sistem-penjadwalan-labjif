import "./bootstrap";
import jQuery from "jquery";
window.$ = jQuery;
import Swal from "sweetalert2";
window.Swal = Swal;
import "toastify-js/src/toastify.css";
import Toastify from "toastify-js";
window.Toastify = Toastify;

$(function () {
    const staticParent = $(document);
    let tooltipElement = null; 
    staticParent.on("mouseenter focus", ".js-tooltip-trigger", function () {
        const trigger = $(this);
        const tooltipText = trigger.data("tooltip-text");

        if (!tooltipText) return;

        tooltipElement = $(`
            <div class="dynamic-tooltip absolute z-50 w-max rounded-md bg-gray-800 px-3 py-1.5 text-sm font-medium text-white shadow-sm">
                ${tooltipText}
                <div class="absolute -bottom-1 left-1/2 h-2 w-2 -translate-x-1/2 rotate-45 transform bg-gray-800"></div>
            </div>
        `);

        $("body").append(tooltipElement);

        const triggerRect = trigger[0].getBoundingClientRect();
        const tooltipRect = tooltipElement[0].getBoundingClientRect();

        let top = triggerRect.top - tooltipRect.height - 8; // 8px untuk jarak
        let left =
            triggerRect.left + triggerRect.width / 2 - tooltipRect.width / 2;

        tooltipElement
            .css({
                top: `${top}px`,
                left: `${left}px`,
            })
            .fadeIn(150);
    });

    staticParent.on("mouseleave blur", ".js-tooltip-trigger", function () {
        if (tooltipElement) {
            tooltipElement.fadeOut(150, function () {
                $(this).remove();
                tooltipElement = null;
            });
        }
    });
});
