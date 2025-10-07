import { Notyf } from "notyf";
import "notyf/notyf.min.css";

const notyf = new Notyf({
  duration: 5000,
  ripple: true,
  dismissible: true,
  position: {
    x: "right",
    y: "top",
  },
  types: [
    {
      type: "success",
      background: "var(--success-color, #28a745)",
      icon: {
        className: "mdi mdi-check-circle",
        tagName: "i",
        color: "#fff",
      },
    },
    {
      type: "error",
      background: "var(--error-color, #dc3545)",
      icon: {
        className: "mdi mdi-close-circle",
        tagName: "i",
        color: "#fff",
      },
    },
    {
      type: "warning",
      background: "var(--warning-color, #ffc107)",
      icon: {
        className: "mdi mdi-alert-circle",
        tagName: "i",
        color: "#000",
      },
    },
  ],
});

export const $notify = notyf;
