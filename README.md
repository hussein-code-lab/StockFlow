# StockFlow - Enterprise Event-Driven Inventory Management System

**StockFlow** is a high-performance, robust Inventory and Order Management System designed with an **Event-Driven Architecture** to handle high-concurrency and ensure maximum data integrity. Built using the modern PHP ecosystem, it optimizes server resources by offloading heavy inventory calculations and logging into asynchronous background queues.

---

## 🚀 Key Architectural Features

### 1. Event-Driven Inventory Ledger
Instead of tightly coupling order creation with database stock changes, StockFlow dispatches an asynchronous `OrderCreated` event. A dedicated background Queue Listener (`ProcessStockDeduction`) processes the payload safely inside a strict database transaction, changing the status and computing prices asynchronously.

### 2. Polymorphic Audit Trail
Every single physical change in the warehouse is securely recorded in a unified `inventory_movements` ledger using **Polymorphic Relations**. 
* **Stock Inbound (Suppliers/Adjustments):** Automatically referenced back to the `Product` model.
* **Stock Outbound (Sales/Customer Orders):** Directly linked to the specific `Order` model, creating a bulletproof, unalterable history trail.

### 3. Strict Real-Time Multi-Stage Validation
To strictly prevent negative stock balances (selling items not physically available), the system executes real-time aggregate checking (`sum('quantity')`) right inside the Filament form interaction, providing an elegant UX with precise error messages before database persistence.

### 4. Dynamic Visual Alert Levels (Reorder Points)
Products are monitored continuously against an immutable `alert_level` configuration. Once the live virtual stock dips below the threshold, the dashboard instantly flags the item with dynamic visual warnings to alert purchasing managers.

---

## 🛠️ Tech Stack & Ecosystem

* **Backend Framework:** Laravel 12 (PHP 8.2+)
* **Admin Portal & UI:** Filament PHP v5 (Advanced Tabled Forms, Badge Columns & Custom Page Mutators)
* **Reactive Core:** Livewire 4
* **Database:** MySQL 8 (Indexed Morph fields & Foreign Constraints)
* **Queue Driver:** Database/Redis

---

## 📸 Core Modules Preview
*(Screenshots representing the Enterprise UX of StockFlow)*

### 🛒 1. Advanced Order Creation
The order creation dashboard allows managers to build dynamic multi-item lists using continuous reactive data syncing to fetch unit prices instantly.

![Order Form](screenshots/1-order-form.png)

### ⚠️ 2. Real-Time Stock Validation
The core safeguard in action: blocking managers from finalizing orders when requested quantities exceed current available balances.

![Stock Validation Error](screenshots/2-stock-validation.png)

### 📦 3. Live Smart Inventory Dashboard
An interactive product list that aggregates calculated virtual balances on-the-fly, gracefully rendering dynamic state flags when thresholds are reached.

![Product Catalog Table](screenshots/3-product-catalog.png)

### 📋 4. Polymorphic Stock Movements / Ledger
A comprehensive audit trail displaying incoming and outgoing shipments, seamlessly indicating types and system-generated references.

![Inventory Movements](screenshots/4-inventory-movements.png)

---

## ⚙️ Installation & Quick Start

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/yourusername/stockflow.git](https://github.com/yourusername/stockflow.git)
   cd stockflow
