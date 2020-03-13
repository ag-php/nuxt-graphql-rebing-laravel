<template>
  <div>
    <h1>Dashboard</h1>
    <br />
    <div>
      <el-row>
        <el-tree
          :data="locationEntries"
          node-key="id"
          :expand-on-click-node="false"
          @node-click="locationChanged"
          class="with-border"
        >
          <span class="custom-tree-node" slot-scope="{ node }">
            <span
              v-bind:class="{
                'location-label': node.label === location.label
              }"
              >{{ node.label.replace(/[^a-z0-9+]+/gi, " ") }}</span
            >
          </span>
        </el-tree>

        <el-date-picker
          v-model="month"
          type="month"
          placeholder="Pick a month"
          :picker-options="datePickerOptions"
          @change="periodChanged"
        >
        </el-date-picker>

        <el-select v-model="targetSelector" @change="targetSelectorChanged">
          <el-option
            v-for="item in selectorOptions"
            :key="item"
            :label="item"
            :value="item"
          >
          </el-option>
        </el-select>
      </el-row>

      <div style="text-align:center" v-loading="!g1TotalsLoaded">
        <panel-group
          label1="Total Revenue"
          :value1="totalRevenue"
          label2="Target Revenue"
          :value2="targetRevenue"
          label3="Total Revenue (LY)"
          :value4="totalRevenueLY"
        />
      </div>

      <br /><br />

      <div style="width:100%;height:500px">
        <bar-chart
          v-if="g1loaded"
          :title="{ text: '6-Month Revenue' }"
          :series="revenueSeries"
          :source="revenueSix"
          style="width:50%; padding: 50px; float:left"
        />
        <div v-else>
          <img src="/spinner.gif" class="spinner" />
        </div>

        <div style="width:50%; padding: 50px; float: right" v-if="g2loaded">
          <h2>Revenue Mix - Top 10 Accounts</h2>
          <el-table :data="revenueAccounts" style="padding: 50px;">
            <el-table-column label="">
              <template slot-scope="scope">
                {{ scope.row.name }}
              </template>
            </el-table-column>
            <el-table-column label="">
              <template slot-scope="scope">
                {{ scope.row.revenue }}
              </template>
            </el-table-column>
          </el-table>
        </div>
        <div v-else>
          <img src="/spinner.gif" class="spinner" />
        </div>
      </div>

      <div class="cogs-charts">
        <line-chart :chart-data="cogsConf" v-if="g5loaded" />
        <div v-else>
          <img src="/spinner.gif" class="spinner" />
        </div>
      </div>

      <!-- && cogsIndividualLoaded -->

      <div v-if="g6loaded && categories.length">
        <div
          v-for="(category, i) in categories"
          :key="i"
          style="width:50%;display:inline-block"
        >
          <bar-chart
            width="100%"
            v-if="cogsIndividual[category['name']].length"
            :title="title(category['name'])"
            :series="cogsIndividualSeries"
            :source="cogsIndividual[category['name']]"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BarChart from "@/components/BarChart";
import LineChart from "@/components/LineChart";
import PanelGroup from "@/components/PanelGroup";

const amt = (d, i) => d.find(r => r.p === i).amount;

export default {
  middleware: "authenticated",
  layout: "app",
  components: {
    LineChart,
    BarChart,
    PanelGroup
  },
  data() {
    return {
      currentPeriod: 1,
      periodNum: 1,
      period: null,
      month: null,
      datePickerOptions: {
        disabledDate(date) {
          return date > new Date() || date < new Date("01/01/2018");
        }
      },
      locationEntries: [],
      location: {
        id: null,
        label: null,
        children: []
      },
      locations: [],
      categories: [],

      g1loaded: false,
      g1TotalsLoaded: false,
      g2loaded: false,
      g5loaded: false,
      g6loaded: false,

      targetRevenue: 0,
      totalRevenue: 0,
      totalRevenueLY: 0,

      actualsCYCOGS: [],
      actualsLYCOGS: [],
      targetCOGS: [],

      cogsIndividual: {},

      intl: new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD"
      }),

      revenueSix: [],
      revenue: [],
      revenueCY: [],
      revenueLY: [],
      revenueAccounts: [
        { name: "Food", revenue: 0 },
        { name: "Liqour", revenue: 0 },
        { name: "Beer", revenue: 0 },
        { name: "Wine", revenue: 0 },
        { name: "Apparel", revenue: 0 }
      ],
      selectorOptions: ["Budget", "Q2 Forecast", "Q3 Forecast", "Q4 Forecast"],
      targetSelector: "Budget",
      months: [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
      ]
    };
  },
  computed: {
    list() {
      let l = [];
      let n = { revenue: "Current Year Revenue" };
      this.revenueCY.forEach((rev, i) => {
        n[rev.name] = rev.revenue;
      });
      l.push(n);
      let m = { revenue: "Last Year Revenue" };
      this.revenueLY.forEach((rev, i) => {
        m[rev.name] = rev.revenue;
      });
      l.push(m);
      return l;
    },
    revenueSeries() {
      return [
        { name: this.period.year - 1, type: "bar" },
        { name: this.period.year, type: "bar" }
      ];
    },
    cogsMonths() {
      let m = this.period.month;
      let j = this.months.indexOf(m);
      let arr1 = this.months.slice(0, j + 1);
      let arr2 = this.months.slice(j + 1);
      let arr3 = arr2.concat(arr1);
      arr3.unshift(m);
      return arr3;
    },
    cogsConf() {
      return {
        title: {
          text: "COGS",
          textAlign: "center",
          left: "center",
          top: "50px"
        },
        xAxis: {
          data: this.cogsMonths,
          boundaryGap: false,
          axisTick: {
            show: false
          }
        },
        grid: {
          left: 10,
          right: 10,
          bottom: 20,
          top: 30,
          containLabel: true
        },
        tooltip: {
          trigger: "axis",
          axisPointer: {
            type: "cross"
          },
          padding: [5, 10]
        },
        yAxis: {
          axisTick: {
            show: false
          }
        },
        legend: {
          data: ["Target COGS", "Current Year COGS", "Prior Year COGS"],
          bottom: "-5px"
        },
        series: [
          {
            name: "Target COGS",
            itemStyle: {
              normal: {
                color: "orange",
                lineStyle: {
                  color: "orange",
                  width: 2
                }
              }
            },
            smooth: true,
            type: "line",
            data: this.targetCOGS,
            animationDuration: 2800,
            animationEasing: "cubicInOut"
          },
          {
            name: "Current Year COGS",
            smooth: true,
            type: "line",
            itemStyle: {
              normal: {
                color: "blue",
                lineStyle: {
                  color: "blue",
                  width: 4
                },
                areaStyle: {
                  color: "lightblue"
                }
              }
            },
            data: this.actualsCYCOGS,
            animationDuration: 2800,
            animationEasing: "quadraticOut"
          },
          {
            name: "Prior Year COGS",
            smooth: true,
            type: "line",
            itemStyle: {
              normal: {
                color: "gray",
                lineStyle: {
                  color: "gray",
                  width: 2
                },
                areaStyle: {
                  color: "gray"
                }
              }
            },
            data: this.actualsLYCOGS,
            animationDuration: 2800,
            animationEasing: "quadraticOut"
          }
        ]
      };
    },
    cogsIndividualLoaded() {
      if (!this.cogsIndividual) {
        return false;
      } else {
        let r = true;
        Object.keys(this.cogsIndividual).forEach((key, index) => {
          if (!this.cogsIndividual[key].length) {
            r = false;
          }
        });
        return r;
      }
    },
    cogsIndividualSeries() {
      return [
        {
          name: "Revenue",
          type: "bar",
          itemStyle: {
            normal: {
              color: "navyblue"
              // lineStyle: {
              //     // color: 'navyblue',
              // },
            }
          }
        },
        {
          name: this.targetSelector,
          type: "line",
          itemStyle: {
            normal: {
              color: "orange",
              lineStyle: {
                color: "orange",
                width: 4
              }
            }
          }
        }
      ];
    }
  },
  async mounted() {
    let init = await this.$store.dispatch("graphs/periodReverse", new Date());
    this.currentPeriod = init;
    this.periodNum = init;

    this.period = await this.$store.dispatch("graphs/period", this.periodNum);
    this.month = new Date("01 " + this.period.month + " " + this.period.year);

    this.locations = await this.$store.dispatch("graphs/locations");

    this.locationEntries = JSON.parse(
      JSON.stringify(
        this.convertToTree(
          this.locations.map(item => ({
            ...item,
            id: item.acct_id,
            children: [],
            label: item.acct
          }))
        )
      )
    );

    this.location = this.locationEntries[0];

    this.categories = await this.$store.dispatch("graphs/categories");

    await this.loadGraphs();
  },
  methods: {
    convertToTree(list) {
      let map = {},
        node,
        roots = [],
        i;
      for (i = 0; i < list.length; i += 1) {
        map[list[i].id] = i;
        list[i].children = [];
      }
      for (i = 0; i < list.length; i += 1) {
        node = list[i];
        if (node.parent !== 0) {
          list[map[node.parent]].children.push(node);
        } else {
          roots.push(node);
        }
      }
      return roots;
    },
    async periodChanged() {
      this.periodNum = await this.$store.dispatch(
        "graphs/periodReverse",
        this.month
      );
      this.period = await this.$store.dispatch("graphs/period", this.periodNum);
      await this.loadGraphs();
    },
    async targetSelectorChanged() {
      return Promise.all([this.loadGraph1Totals(), this.loadGraph5()]);
    },

    async locationChanged(node) {
      this.location = node;
      await this.loadGraphs();
    },

    async loadGraphs() {
      return Promise.all([
        this.loadGraph1(),
        this.loadGraph1Totals(),
        this.loadGraph2(),
        this.loadGraph5(),
        this.loadgraphSixIndividual()
      ]);
    },
    async loadGraph1() {
      this.g1loaded = false;
      let g1dataA = await this.$store.dispatch("graphs/graphOne", {
        location: this.location.label,
        locationId: this.location.id,
        isParent: this.location.acct_type === "parent",
        period: this.periodNum
      });
      this.revenueCY = [];

      g1dataA.forEach(e =>
        this.revenueCY.push({
          name: e.m,
          month: e.p,
          year: e.y,
          revenue: e.amount
        })
      );

      let g1dataB = await this.$store.dispatch("graphs/graphOne", {
        location: this.location.label,
        locationId: this.location.id,
        isParent: this.location.acct_type === "parent",
        period: this.periodNum - 12
      });
      this.revenueLY = [];
      g1dataB.forEach(e =>
        this.revenueLY.push({
          name: e.m,
          month: e.p,
          year: e.y,
          revenue: e.amount
        })
      );

      this.revenueSix = [];

      this.revenueLY.forEach(e => {
        let b = this.revenueCY.find(j => j.name == e.name);
        let r = b ? b.revenue : 0;
        this.revenueSix.push([e.name, e.revenue, r]);
      });
      console.log("revenueSix", this.revenueSix);
      this.g1loaded = true;
    },
    async loadGraph1Totals() {
      let periodLY = this.periodNum - 13;
      this.g1TotalsLoaded = false;

      this.targetRevenue = await this.$store.dispatch("graphs/graphOneTotals", {
        location: this.location.label,
        locationId: this.location.id,
        isParent: this.location.acct_type === "parent",
        period: this.periodNum,
        selector: this.targetSelector
      });

      this.totalRevenue = await this.$store.dispatch("graphs/graphOneTotals", {
        location: this.location.label,
        locationId: this.location.id,
        isParent: this.location.acct_type === "parent",
        period: this.periodNum,
        selector: "actuals"
      });

      if (periodLY >= 1) {
        this.totalRevenueLY = await this.$store.dispatch(
          "graphs/graphOneTotals",
          {
            location: this.location.label,
            locationId: this.location.id,
            isParent: this.location.acct_type === "parent",
            period: periodLY,
            selector: "actuals"
          }
        );
      } else {
        this.totalRevenueLY = 0;
      }

      this.g1TotalsLoaded = true;
    },
    async loadGraph2() {
      this.g2loaded = false;
      let g2data = await this.$store.dispatch("graphs/graphTwo", {
        location: this.location.label,
        locationId: this.location.id,
        isParent: this.location.acct_type === "parent",
        period: this.periodNum
      });

      this.revenueAccounts = this.accounts(g2data);
      this.g2loaded = true;
    },
    async loadGraph5() {
      this.g5loaded = false;
      let g5data = await this.$store.dispatch("graphs/graphFiveCOGS", {
        location: this.location.label,
        locationId: this.location.id,
        isParent: this.location.acct_type === "parent",
        selector: this.targetSelector,
        p: this.periodNum
      });
      this.actualsCYCOGS = g5data.actualsCYCOGS;
      this.actualsLYCOGS = g5data.actualsLYCOGS;
      this.targetCOGS = g5data.targetCOGS;
      this.g5loaded = true;
    },
    async loadgraphSixIndividual() {
      this.g6loaded = false;

      this.categories.forEach(c => {
        this.cogsIndividual[c["name"]] = [];
      });

      this.categories.forEach(async c => {
        let g6data = await this.$store.dispatch("graphs/graphSixIndividual", {
          location: this.location.label,
          locationId: this.location.id,
          isParent: this.location.acct_type === "parent",
          selector: this.targetSelector,
          p: this.periodNum,
          category: c["id"]
        });

        let r = [];
        g6data.actualsData.forEach((g6, i) => {
          r.push([
            this.cogsMonths[i] + "(" + i + ")",
            g6data.actualsData[i],
            g6data.selectorData[i]
          ]);
        });

        this.cogsIndividual[c["name"]] = r;
      });

      this.g6loaded = true;
    },

    accounts(d) {
      let r = [];
      d.forEach(e =>
        r.push({
          name: e.account.replace(/\d/g, "").trim(),
          revenue: this.intl.format(e.amount)
        })
      );
      return r;
    },
    title(text) {
      return {
        text,
        textAlign: "center",
        left: "center",
        top: "75px"
      };
    },
    sumSource(source, columnName) {
      let sum = 0.0;
      source.forEach((r, i) => {
        sum += parseFloat(r[columnName]);
      });
      return sum;
    }
  }
};
</script>

<style scoped lang="scss">
.rev-table {
  width: 90%;
  padding: 50px;
  text-align: center;
  margin-left: auto;
  margin-right: auto;
}
.rev-title {
  text-transform: uppercase;
  font-size: 20px;
}
.rev-value {
  font-size: 42px;
}
.cogs-chart {
  width: 100%;
  height: 600px;
  padding: 50px;
}
.spinner {
  width: 300px;
  float: left;
}

.el-tree {
  width: 220px;
  &.with-border {
    border: 3px solid black;
  }

  /deep/ .el-tree-node {
    &.is-current {
      .el-tree-node__content {
        .custom-tree-node {
          .location-label {
            font-weight: 600;
          }
        }
      }
    }
  }
}

.el-row {
  display: flex;
  align-items: flex-start;
  flex-direction: row;
}
</style>