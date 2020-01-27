<template>
    <div
        :id="svgId"
        :height="height"
        :width="width" />
</template>

<script>
export default {
    props: {
        svgId: {
            type: String,
            required: true
        },
        selectPaths: {
            type: Array,
            required: false,
            default: () => []
        },
        filename: {
            type: String,
            required: true
        },
        findColors: {
            type: Array,
            default: () => []
        },
        replaceColors: {
            type: Array,
            default: () => []
        },
        width: {
            type: String,
            default: ''
        },
        height: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            s: null
        }
    },
    computed: {
        colorCount() {
            return this.findColors.length
        }
    },
    mounted() {
        this.s = this.$snap('#' + this.svgId)
        this.$snap.load(this.filename, this.process)
    },
    methods: {
        process(data) {
            this.s.append(data)
            let svgElement = document.getElementById(this.svgId)
            if (!svgElement) return
            if (this.selectPaths.length) {
                this.selectPaths.forEach(p => this.paintItem(svgElement.getElementById(p)))
            } else {
                let paths = svgElement.getElementsByTagNameNS(
                    'http://www.w3.org/2000/svg',
                    'path'
                )
                for (let item of paths) {
                    item.setAttribute('transform', 'scale(0.11)')
                    this.paintItem(item)
                }
            }

        },
        paintItem(item) {
            let fill = item.getAttribute('fill')
            if (fill) {
                for (let i = 0; i < this.colorCount; i++) {
                    if (fill === this.findColors[i]) {
                        item.setAttribute('fill', this.replaceColors[i])
                        break
                    }
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
