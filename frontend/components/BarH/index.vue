<template>
  <div class="board-column">
    <div class="bridge-bar" :class="barTheme">
    </div>
  </div>
</template>

<script>
export default {
  name: "BarH",
  components: {},
  props: {
    barTheme: {
      type: String,
      default: "blue-black"
    },
  },
  methods: {},
  mounted() {
  }
};
</script>
<style lang="scss" scoped>
.board-column {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  .bridge-bar {
    width: 100%;
    min-height: 4px;
    height: 4px;
    &.blue-black {
      background-image: linear-gradient(to right, #5acae8, black);
    }

    &.black-blue {
      background-image: linear-gradient(to right, black, #5acae8);
    }

    &.black-green {
      background-image: linear-gradient(to right, black, #6eba5c);
    }
    
  }
}
</style>

