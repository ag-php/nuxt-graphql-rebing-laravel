<template>
  <div class="board-column" :class="boxTheme">
    <div class="titie-box" :class="{'shorter-header': !hasBorderForContent}">
      <slot name="header"></slot>
    </div>
    <div class="content" :class="{'with-border': hasBorderForContent}">
      <slot name="content"></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "BoxWithBorder",
  components: {},
  props: {
    header: {
      type: String,
      default: "Header"
    },
    hasBorderForContent: {
      type: Boolean,
      default: true
    },
    boxTheme: {
      type: String,
      default: "green-blue"
    }
  },
  methods: {}
};
</script>
<style lang="scss" scoped>
.board-column {
  overflow: hidden;
  font-size: 12px;
  font-weight: 600;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;

  .titie-box {
    width: 100%;
    min-height: 48px;
    height: 48px;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px solid black;
    font-size: 18px;
    font-weight: 600;
    &.shorter-header {
      width: 80%;
    }
  }
  .content {
    width: 100%;
    background: white;
    &.with-border {
      border-left: 2px solid black;
      border-right: 2px solid black;
      border-bottom: 2px solid black;
    }
    justify-content: center;
    align-items: center;
    font-size: 8px !important;
    .el-table {
      font-size: 10px !important;
      /deep/ .cell {
        padding-left: 0px !important;
        padding-right: 0px !important;
        display: flex;
        justify-content: center;
        align-items: center;
      }
      /deep/ tr {
        th {
          .cell {
            -webkit-transform: scale(0.8, 1);
            -moz-transform: scale(0.8, 1);
            -ms-transform: scale(0.8, 1);
            -o-transform: scale(0.8, 1);
            transform: scale(0.8, 1);
            color: black;
            font-weight: 900;
          }
        }
        th:nth-child(2) {
          background: #5acae8 !important;
        }
        td:first-child {
          .cell{
            color: black;
            font-weight: 900;
          }
        }
        td:nth-child(2) {
          background: #5acae8 !important;
        }
        th:nth-child(4) {
          background: #6eba5c !important;
        }
        td:nth-child(4) {
          background: #6eba5c !important;
        }
      }
    }
  }
}
</style>

