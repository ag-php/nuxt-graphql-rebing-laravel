<template></template>
<script>
/*
 * Instructions: complete all TODOs.
 *
 * Feel free to upgrade the code if you see areas of opportunity.
 * (Don't assume the starter code is good.)
 *
 * This VueJS component draws boxes on an HTML canvas
 * See boxes example image in folder
 * A box is defined by it's min and max x/y points.
 *
 * Bonus points - mock up input data and construct a test case.
 *
 */

import Vue from "vue";
export default Vue.extend({
  props: [
    "ord",
    "box_list",
    "current_box",
    "refresh",
    "mouse_position",
    "draw_mode",
    "canvas_transform",
    "show_annotations"
  ],

  /* ord, order of drawing on canvas
   * box_list, list of dictionaries,
   * current_box, dictionary,
   * refresh, integer / hack for forcing reactive property change
   * mouse_position, dictionary, x, y
   * draw_mode, boolean
   * canvas_transform, dictionary,
   * show_annotations, boolean
   * */
  data() {
    return {
      ord: 1,
      box_list: [],
      current_box: null,
      refresh: 0,
      mouse_position: null,
      draw_mode: true,
      canvas_trasform: {
        scale: 1
      },
      show_annotations: true
    };
  },
  methods: {
    draw: function(ctx, done) {
      if (this.show_annotations == true) {
        function toInt(n) {
          return Number(n);
        }

        let circle_size = 8 / this.canvas_transform["scale"];
        let font_size = 20 / this.canvas_transform["scale"];
        ctx.font = font_size + "px Verdana";

        function draw_circle(circle_size, x, y, ctx) {
          ctx.beginPath();
          ctx.arc(x, y, circle_size, 0, 2 * Math.PI, false);
          ctx.lineWidth = 3;
          ctx.strokeStyle = "#FF0000";
          ctx.stroke();
        }

        let boxes = []; // TODO grab box_list

        for (var i in boxes) {
          let box = boxes[i];

          if (box.soft_delete != true) {
            if (box.label.is_visible == null || box.label.is_visible == true) {
              ctx.beginPath();
              ctx.lineWidth = "2";

              let r = box.label.colour.rgba.r;
              // TODO get other colours

              if (box.selected == true) {
                ctx.fillStyle = "blue";
              } else {
                ctx.fillStyle = "rgba(" + r + "," + g + "," + b + ", 1)";
              }

              ctx.textAlign = "start";

              // TODO handle if label is undefined
              ctx.fillText(box.label && box.label?box.label.name : "", toInt(box.x_min), toInt(box.y_min));

              // TODO draw circles (using eariler created function) at box.[x/y]_min and box.[x/y]_max

              ////

              // TODO draw dashed line if special condition is true else draw solid line

              if (box.special_condition == true) {
              } else {
              }

              ctx.fill();

              ctx.fillStyle = // TODO RGBA fill style
                // TODO draw rectangle

                ctx.fill();

              ctx.closePath();

              if (this.draw_mode == false) {
                if (true) {
                  /* TODO
                   * if the mouse position is within the rectangle and/or circles we drew
                   * emit an event 'box_hover', with the current index 'i',
                   * this.mouse_position.raw.x and this.mouse_position.raw.y
                   * */
                }
              }

              if (box.selected == true) {
                ctx.strokeStyle = "blue";
              } else {
                ctx.strokeStyle = box.label.colour.hex;
              }
              ctx.stroke();
            }
          }
        }
      }
      done();
    }
  }
});
</script>
