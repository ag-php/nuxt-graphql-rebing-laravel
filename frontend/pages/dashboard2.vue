<template>
  <div>
    <h1>Dashboard</h1>
    <br />
    <div>
      <el-select v-model="location" placeholder="Locations" @change="locationChanged">
        <el-option label="Roll Up" value></el-option>
        <el-option
          v-for="item in locations"
          :key="item"
          :label="item.replace(/[^a-z0-9+]+/gi, ' ')"
          :value="item"
        ></el-option>
      </el-select>

      <el-date-picker
        v-model="month"
        type="month"
        placeholder="Pick a month"
        :picker-options="datePickerOptions"
        @change="getPeriodNum"
      ></el-date-picker>

      <!-- <table class="rev-table">
                <tr>
                    <td>
                        <h3 class="rev-title">Total Revenue</h3>
                        <h2 class="rev-value">{{ intl.format(sumSource(revenueCY, 'revenue')) }}</h2>
                    </td>
                    <td>
                        <h3 class="rev-title">Target Revenue</h3>
                        <h2 class="rev-value">-.-</h2>
                    </td>
                    <td>
                        <h3 class="rev-title">Total Revenue (2018)</h3>
                        <h2 class="rev-value">{{ intl.format(sumSource(revenueLY, 'revenue')) }}</h2>
                    </td>
                </tr>
      </table>-->

      <br />
      <br />

      <div style="width:100%;height:500px">
        <bar-chart
          v-if="g1loaded"
          :title="{ text: '6-Month Revenue' }"
          :series="revenueSeries"
          :source="revenueSix"
          style="width:50%; padding: 50px; float:left"
        />

        <div style="width:50%; padding: 50px; float: right">
          <h2>Revenue Mix - Top 10 Accounts</h2>
          <el-table v-if="g2loaded" :data="revenueAccounts" style="padding: 50px;">
            <el-table-column label>
              <template slot-scope="scope">{{ scope.row.name }}</template>
            </el-table-column>
            <el-table-column label>
              <template slot-scope="scope">{{ scope.row.revenue }}</template>
            </el-table-column>
          </el-table>
        </div>
      </div>

      <el-select v-model="cogsSelector" placeholder="Selector" @change="cogsSelectorChanged">
        <el-option v-for="item in cogsOptions" :key="item" :label="item" :value="item"></el-option>
      </el-select>

      <div class="cogs-charts">
        <line-chart :chart-data="cogsConf" v-if="g5loaded" />
      </div>

      <el-row :gutter="10">
        <el-col :xs="4" :sm="6" :md="8" :lg="9" :xl="11">
          <bar-chart :title="title('Food')" :series="series('food')" :source="source('food')" />
        </el-col>
        <el-col :xs="4" :sm="6" :md="8" :lg="9" :xl="11">
          <bar-chart :series="series('beer')" :source="source('beer')" :title="title('Beer')" />
        </el-col>
      </el-row>
      <el-row :gutter="10">
        <el-col :xs="4" :sm="6" :md="8" :lg="9" :xl="11">
          <bar-chart :series="series('wine')" :source="source('wine')" :title="title('Wine')" />
        </el-col>
        <el-col :xs="4" :sm="6" :md="8" :lg="9" :xl="11">
          <bar-chart
            :series="series('apparel')"
            :source="source('apparel')"
            :title="title('Apparel')"
          />
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import BarChart from "@/components/BarChart";
import LineChart from "@/components/LineChart";

const amt = (d, i) => d.find(r => r.p === i).amount;

export default {
  middleware: "authenticated",
  layout: "app",
  components: {
    LineChart,
    BarChart
  },
  async mounted() {
    let init = await this.$store.dispatch("graphs/periodReverse", new Date());
    this.currentPeriod = init;
    this.periodNum = init;

    this.period = await this.$store.dispatch("graphs/period", this.periodNum);
    this.month = new Date("01 " + this.period.month + " " + this.period.year);

    this.locations = await this.$store.dispatch("graphs/locations");
    this.location = this.locations[0];

    await this.loadGraphs();
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
      location: null,
      locations: [],

      g1loaded: false,
      g2loaded: false,
      g5loaded: false,

      actualsCYCOGS: [],
      actualsLYCOGS: [],
      targetCOGS: [],

      intl: new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD"
      }),
      revenueSeries: [{ type: "bar" }, { type: "bar" }],
      revenueSix: [],
      revenue: [
        ["Jan", 0, 0],
        ["Feb", 0, 0],
        ["Mar", 0, 0],
        ["Apr", 0, 0],
        ["May", 0, 0],
        ["Jun", 0, 0],
        ["Jul", 0, 0],
        ["Aug", 0, 0],
        ["Sep", 0, 0],
        ["Oct", 0, 0],
        ["Nov", 0, 0],
        ["Dec", 0, 0]
      ],
      revenueCY: [
        { name: "Jan", month: 1, year: 2019, revenue: 0 },
        { name: "Feb", month: 2, year: 2019, revenue: 0 },
        { name: "Mar", month: 3, year: 2019, revenue: 0 },
        { name: "Apr", month: 4, year: 2019, revenue: 0 },
        { name: "May", month: 5, year: 2019, revenue: 0 },
        { name: "Jun", month: 6, year: 2019, revenue: 0 },
        { name: "Jul", month: 7, year: 2019, revenue: 0 },
        { name: "Aug", month: 8, year: 2019, revenue: 0 },
        { name: "Sep", month: 9, year: 2019, revenue: 0 },
        { name: "Oct", month: 10, year: 2019, revenue: 0 },
        { name: "Nov", month: 11, year: 2019, revenue: 0 },
        { name: "Dec", month: 12, year: 2019, revenue: 0 }
      ],
      revenueLY: [
        { name: "Jan", month: 1, year: 2018, revenue: 0 },
        { name: "Feb", month: 2, year: 2018, revenue: 0 },
        { name: "Mar", month: 3, year: 2018, revenue: 0 },
        { name: "Apr", month: 4, year: 2018, revenue: 0 },
        { name: "May", month: 5, year: 2018, revenue: 0 },
        { name: "Jun", month: 6, year: 2018, revenue: 0 },
        { name: "Jul", month: 7, year: 2018, revenue: 0 },
        { name: "Aug", month: 8, year: 2018, revenue: 0 },
        { name: "Sep", month: 9, year: 2018, revenue: 0 },
        { name: "Oct", month: 10, year: 2018, revenue: 0 },
        { name: "Nov", month: 11, year: 2018, revenue: 0 },
        { name: "Dec", month: 12, year: 2018, revenue: 0 }
      ],
      revenueAccounts: [
        { name: "Food", revenue: 0 },
        { name: "Liqour", revenue: 0 },
        { name: "Beer", revenue: 0 },
        { name: "Wine", revenue: 0 },
        { name: "Apparel", revenue: 0 }
      ],
      cogsOptions: ["Budget", "Q2 Forecast", "Q3 Forecast", "Q4 Forecast"],
      cogsSelector: "Budget",
      CGS: {
        food: [
          { name: "Jan", month: 1, year: 2019, cgs: 0 },
          { name: "Feb", month: 2, year: 2019, cgs: 0 },
          { name: "Mar", month: 3, year: 2019, cgs: 0 },
          { name: "Apr", month: 4, year: 2019, cgs: 0 },
          { name: "May", month: 5, year: 2019, cgs: 0 },
          { name: "Jun", month: 6, year: 2019, cgs: 0 },
          { name: "Jul", month: 7, year: 2019, cgs: 0 },
          { name: "Aug", month: 8, year: 2019, cgs: 0 },
          { name: "Sep", month: 9, year: 2019, cgs: 0 },
          { name: "Oct", month: 10, year: 2019, cgs: 0 },
          { name: "Nov", month: 11, year: 2019, cgs: 0 },
          { name: "Dec", month: 12, year: 2019, cgs: 0 }
        ],
        beer: [
          { name: "Jan", month: 1, year: 2019, cgs: 0 },
          { name: "Feb", month: 2, year: 2019, cgs: 0 },
          { name: "Mar", month: 3, year: 2019, cgs: 0 },
          { name: "Apr", month: 4, year: 2019, cgs: 0 },
          { name: "May", month: 5, year: 2019, cgs: 0 },
          { name: "Jun", month: 6, year: 2019, cgs: 0 },
          { name: "Jul", month: 7, year: 2019, cgs: 0 },
          { name: "Aug", month: 8, year: 2019, cgs: 0 },
          { name: "Sep", month: 9, year: 2019, cgs: 0 },
          { name: "Oct", month: 10, year: 2019, cgs: 0 },
          { name: "Nov", month: 11, year: 2019, cgs: 0 },
          { name: "Dec", month: 12, year: 2019, cgs: 0 }
        ],
        wine: [
          { name: "Jan", month: 1, year: 2019, cgs: 0 },
          { name: "Feb", month: 2, year: 2019, cgs: 0 },
          { name: "Mar", month: 3, year: 2019, cgs: 0 },
          { name: "Apr", month: 4, year: 2019, cgs: 0 },
          { name: "May", month: 5, year: 2019, cgs: 0 },
          { name: "Jun", month: 6, year: 2019, cgs: 0 },
          { name: "Jul", month: 7, year: 2019, cgs: 0 },
          { name: "Aug", month: 8, year: 2019, cgs: 0 },
          { name: "Sep", month: 9, year: 2019, cgs: 0 },
          { name: "Oct", month: 10, year: 2019, cgs: 0 },
          { name: "Nov", month: 11, year: 2019, cgs: 0 },
          { name: "Dec", month: 12, year: 2019, cgs: 0 }
        ],
        apparel: [
          { name: "Jan", month: 1, year: 2019, cgs: 0 },
          { name: "Feb", month: 2, year: 2019, cgs: 0 },
          { name: "Mar", month: 3, year: 2019, cgs: 0 },
          { name: "Apr", month: 4, year: 2019, cgs: 0 },
          { name: "May", month: 5, year: 2019, cgs: 0 },
          { name: "Jun", month: 6, year: 2019, cgs: 0 },
          { name: "Jul", month: 7, year: 2019, cgs: 0 },
          { name: "Aug", month: 8, year: 2019, cgs: 0 },
          { name: "Sep", month: 9, year: 2019, cgs: 0 },
          { name: "Oct", month: 10, year: 2019, cgs: 0 },
          { name: "Nov", month: 11, year: 2019, cgs: 0 },
          { name: "Dec", month: 12, year: 2019, cgs: 0 }
        ]
      },
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
    cogsConf() {
      return {
        title: {
          text: "COGS",
          textAlign: "center",
          left: "center",
          top: "50px"
        },
        xAxis: {
          data: [
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
          ],
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
    }
  },
  methods: {
    async locationChanged() {
      await this.loadGraphs();
    },
    async loadGraphs() {
      return Promise.all([
        this.loadGraph1(),
        this.loadGraph2(),
        this.loadGraph5()
      ]);
    },
    async loadGraph1() {
      this.g1loaded = false;
      let g1dataA = await this.$store.dispatch("graphs/graphOne", {
        location: this.location,
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
        location: this.location,
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
      this.g1loaded = true;
    },
    async loadGraph2() {
      this.g2loaded = false;
      let g2data = await this.$store.dispatch("graphs/graphTwo", {
        location: this.location,
        period: this.periodNum
      });
      this.revenueAccounts = this.accounts(g2data);
      this.g2loaded = true;
    },
    async loadGraph5() {
      this.g5loaded = false;
      let g5data = await this.$store.dispatch("graphs/graphFiveCOGS", {
        location: this.location,
        selector: this.cogsSelector,
        p: this.currentPeriod
      });
      this.actualsCYCOGS = g5data.actualsCYCOGS;
      this.actualsLYCOGS = g5data.actualsLYCOGS;
      this.targetCOGS = g5data.targetCOGS;
      this.g5loaded = true;
    },
    async getPeriodNum() {
      this.periodNum = await this.$store.dispatch(
        "graphs/periodReverse",
        this.month
      );
      this.loadGraph1();
      this.loadGraph2();
    },
    rev(d) {
      let p1y = d.find(r => r.p === "P1").y;
      let p13y = d.find(r => r.p === "P13").y;
      return [
        ["Jan", amt(d, "P1"), amt(d, "P13")],
        ["Feb", amt(d, "P2"), amt(d, "P14")],
        ["Mar", amt(d, "P3"), amt(d, "P15")],
        ["Apr", amt(d, "P4"), amt(d, "P16")],
        ["May", amt(d, "P5"), amt(d, "P17")],
        ["Jun", amt(d, "P6"), amt(d, "P18")],
        ["Jul", amt(d, "P7"), amt(d, "P19")],
        ["Aug", amt(d, "P8"), amt(d, "P20")],
        ["Sep", amt(d, "P9"), amt(d, "P21")],
        ["Oct", amt(d, "P10"), amt(d, "P22")],
        ["Nov", amt(d, "P11"), amt(d, "P23")],
        ["Dec", amt(d, "P12"), amt(d, "P24")]
      ];
    },
    revCY(d) {
      let r = [];
      let f = d.filter(e => parseInt(e.p.replace(/\D/g, "")) > 12);
      f.forEach(e => {
        r.push({
          name: e.m,
          month: e.mn,
          year: e.y,
          revenue: e.amount
        });
      });
      return r;
    },
    revLY(d) {
      let r = [];
      let f = d.filter(e => parseInt(e.p.replace(/\D/g, "")) <= 12);
      f.forEach(e => {
        r.push({
          name: e.m,
          month: e.mn,
          year: e.y,
          revenue: e.amount
        });
      });
      return r;
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
    series(product) {
      return [
        {
          type: "bar",
          itemStyle: {
            normal: {
              color: "navyblue",
              lineStyle: {
                color: "navyblue"
                // width: 4
              }
            }
          }
        },
        {
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
    },
    source(product) {
      let total = 0;
      this.CGS[product].forEach(row => (total += row.cgs));
      let avg = total / this.CGS[product].length;
      let s = [];
      this.CGS[product].forEach(row => s.push([row.name, row.cgs, avg]));
      return s;
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
    },
    cogsSelectorChanged() {
      this.loadGraph5();
    }
  }
};
</script>

<style scoped>
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
</style>