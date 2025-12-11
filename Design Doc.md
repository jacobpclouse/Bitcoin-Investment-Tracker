# Investment Comparison Tool - Design Document

## Product Overview
A web application that allows users to compare hypothetical investment returns across different asset classes (stocks, ETFs, crypto, precious metals) by visualizing what their money would have grown to over specified time periods.

## Core Value Proposition
Answers the question: "What if I had invested $X in Y on this date?" and lets users compare multiple scenarios side-by-side.

---

## MVP Features (Version 1.0 - STOP HERE FIRST)

### 1. Asset Selection
- Support for **5-10 popular assets only**:
  - S&P 500 (SPY)
  - Bitcoin
  - Gold
  - Silver
  - 2-3 popular ETFs (QQQ, VTI, etc.)
- Simple dropdown selector, no search functionality yet

### 2. Investment Calculator
- Input fields:
  - Initial investment amount
  - Start date (date picker)
  - End date (date picker, defaults to today)
- Calculate button that processes one investment at a time
- Display results:
  - Final value
  - Total return ($)
  - Percentage gain/loss
  - Simple line chart showing growth over time

### 3. Basic Comparison (2-3 investments max)
- Add ability to calculate multiple scenarios
- Side-by-side line chart with different colored lines
- Simple table showing final values for each investment
- All normalized to same start value for visual comparison

### 4. Data Management
- Fetch historical daily closing prices from Alpha Vantage or Yahoo Finance
- Cache in database (don't refetch historical data)
- Background job to update latest prices daily
- Handle weekends/holidays (use last available trading day)

### 5. Basic UI/UX
- Clean, simple form layout
- Responsive design (mobile-friendly)
- Chart with legend and tooltips
- Basic error handling (invalid dates, API failures)

**STOP CRITERIA FOR MVP:** When users can compare 2-3 investments with a chart and see results. Test with 10 real users. Get feedback before building more.

---

## Version 1.5 Features (Add if MVP gets traction)

### 6. Enhanced Asset Library
- Expand to 50+ assets
- Add search/autocomplete for ticker symbols
- Categorize by type (stocks, ETFs, crypto, commodities)

### 7. Recurring Investment (DCA - Dollar Cost Averaging)
- Add option for monthly recurring investments instead of lump sum
- Input: monthly contribution amount
- Calculate total invested vs. total value
- Show cost-basis over time

### 8. User Accounts (Optional but recommended)
- Save favorite comparisons
- History of past analyses
- No authentication required for basic use (don't add friction)

### 9. Preset Comparisons
- "S&P 500 vs Bitcoin vs Gold (Last 5 years)"
- "Tech vs Value (Last decade)"
- Pre-built scenarios users can click and view

---

## Version 2.0 Features (Only if product shows strong engagement)

### 10. Advanced Analytics
- Volatility comparison (standard deviation)
- Sharpe ratio calculations
- Maximum drawdown visualization
- Risk-adjusted returns

### 11. Portfolio Allocation
- Split investment across multiple assets
- Rebalancing scenarios
- Pie chart showing allocation

### 12. Export & Sharing
- Download charts as PNG
- Export data to CSV
- Shareable links to specific comparisons

### 13. Dividend Reinvestment
- Toggle for total return (with dividends) vs price return
- Show dividend impact separately

---

## Features to NEVER Build (Scope Creep Traps)

❌ **Real-time trading** - You're not a brokerage  
❌ **Investment recommendations** - Legal/regulatory nightmare  
❌ **Crypto wallet integration** - Massive scope, security concerns  
❌ **Tax calculations** - Too complex, varies by jurisdiction  
❌ **News feeds** - Content moderation headache  
❌ **Social features** - Comments, likes, follows add huge complexity  
❌ **Mobile apps** - Web-first, responsive design is enough  
❌ **AI predictions** - Liability and accuracy issues  

---

## Technical Implementation Phases

### Phase 1: MVP Backend
- Laravel API routes for calculation
- Service class for data provider (Alpha Vantage/Yahoo)
- Database schema: `assets`, `daily_prices`, `user_comparisons` (if auth)
- Redis cache for API responses
- Queue job for daily price updates

### Phase 2: MVP Frontend
- Vue component: InvestmentForm
- Vue component: ComparisonChart (using Chart.js)
- Vue component: ResultsTable
- Vuex/Pinia for state management

### Phase 3: Enhancement Iterations
- Only add features based on user feedback
- Track which features users actually use
- Remove features with <5% usage

---

## Success Metrics

**MVP Success (decide to continue):**
- 50+ users complete at least one comparison
- 20% return to do a second comparison
- Average session time >2 minutes

**Version 1.5 Success:**
- 500+ monthly active users
- 40% use the tool more than once

**Stop/Pivot Signals:**
- <10 users in first month
- Users bounce after one use (no repeat usage)
- Negative feedback on core value prop

---

## Timeline Estimates

- **MVP:** 2-3 weeks (1 developer)
- **Version 1.5:** +1-2 weeks
- **Version 2.0:** +2-3 weeks

**Golden Rule:** Ship MVP in under 3 weeks. If it takes longer, you're over-building.

---

## When to Stop

Stop building new features when:
1. ✅ Users can accomplish the core task (compare investments)
2. ✅ The tool is reliable and fast
3. ✅ You've shipped MVP and collected user feedback
4. ❌ You're adding features users didn't request
5. ❌ Features are for "nice to have" not "need to have"
6. ❌ You're building before validating the previous feature worked

**Remember:** It's easier to add features later than to maintain unused ones. Start small, validate, then grow.