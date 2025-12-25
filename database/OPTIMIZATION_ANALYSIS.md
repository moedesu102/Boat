# Phân Tích & Tối Ưu Hóa Database

## 📊 Tổng Quan Database Schema

Database hiện tại có **17 bảng** với các mối quan hệ phức tạp. Dưới đây là phân tích chi tiết và các đề xuất tối ưu hóa.

---

## 🔍 Các Vấn Đề Đã Phát Hiện

### 1. **Thiếu Indexes Quan Trọng** ⚠️ CRITICAL

#### A. Bảng `rentals` (Bảng quan trọng nhất - nhiều query)
**Vấn đề:**
- Thiếu index trên `start_time`, `end_time` → Query theo khoảng thời gian chậm
- Thiếu index trên `status` → Filter theo trạng thái chậm
- Thiếu index trên `payment_status` → Query thanh toán chậm
- Thiếu composite index `(boat_id, start_time, end_time)` → Check availability chậm
- Thiếu composite index `(branch_id, status, start_time)` → Dashboard queries chậm
- `invoice_id` không có foreign key constraint

**Impact:** ⚠️ **HIGH** - Bảng này được query nhiều nhất

#### B. Bảng `boats`
**Vấn đề:**
- Thiếu index trên `status` → Filter available boats chậm
- Thiếu index trên `online_bookable`, `available_online` → Website queries chậm
- Thiếu composite index `(branch_id, status, online_bookable)` → Common query pattern
- Thiếu index trên `type_id` (đã có FK nhưng cần verify)

**Impact:** ⚠️ **HIGH** - Core business logic

#### C. Bảng `customers`
**Vấn đề:**
- Thiếu index trên `status` → Filter active customers
- Thiếu index trên `is_active` → Active user queries
- Thiếu index trên `email` (có unique nhưng cần verify performance)
- Thiếu composite index `(status, is_active)` → Common filter

**Impact:** ⚠️ **MEDIUM**

#### D. Bảng `invoices`
**Vấn đề:**
- Thiếu index trên `status` → Filter unpaid invoices
- Thiếu index trên `due_date` → Overdue invoices query
- Thiếu composite index `(customer_id, status)` → Customer invoice history
- Thiếu composite index `(branch_id, status, due_date)` → Reports

**Impact:** ⚠️ **MEDIUM**

#### E. Bảng `boat_images`
**Vấn đề:**
- Thiếu index trên `is_primary` → Get primary image query
- Thiếu composite index `(boat_id, is_primary)` → Common query

**Impact:** ⚠️ **LOW**

#### F. Bảng `pricing_tiers`
**Vấn đề:**
- Thiếu index trên `active` → Filter active tiers
- Thiếu index trên `is_default` → Get default tier
- Thiếu composite index `(branch_id, active, sort_order)` → Pricing queries

**Impact:** ⚠️ **MEDIUM**

### 2. **Data Redundancy** ⚠️ MEDIUM

#### A. Bảng `invoices`
**Vấn đề:**
- Lưu trữ `customer_name`, `customer_email`, `customer_phone` → Duplicate data
- Lưu trữ `boat_name`, `rental_date`, `rental_time` → Duplicate data

**Giải pháp:**
- ✅ **Nên giữ lại** vì đây là snapshot data cho invoice (historical data)
- Invoice không nên thay đổi khi customer/rental thay đổi
- Đây là best practice cho financial records

### 3. **Missing Foreign Key Constraints** ⚠️ MEDIUM

#### A. Bảng `rentals`
- `invoice_id` không có foreign key constraint
- Nên thêm: `foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null')`

**Impact:** ⚠️ **MEDIUM** - Data integrity

### 4. **JSON Fields** ⚠️ LOW

#### A. `prices_json` trong `boats`
- Lưu trữ pricing data dạng JSON
- **Vấn đề:** Khó query, không có index
- **Giải pháp:** Đã có bảng `boat_pricing_tiers` → Nên migrate data và remove field này

#### B. `addons_json` trong `rentals`
- Lưu trữ addons dạng JSON
- **Vấn đề:** Duplicate với `rental_addons` table
- **Giải pháp:** Nên remove field này, chỉ dùng `rental_addons` table

### 5. **Soft Deletes** ⚠️ LOW

**Vấn đề:**
- Không có soft deletes cho các bảng quan trọng
- Mất data khi delete

**Giải pháp:**
- Cân nhắc thêm soft deletes cho: `boats`, `customers`, `rentals`, `invoices`
- Trade-off: Tăng storage, phức tạp queries

### 6. **Full-Text Search** ⚠️ LOW

**Vấn đề:**
- Không có full-text indexes cho search
- Search trên `name`, `description` sẽ chậm

**Giải pháp:**
- Thêm full-text indexes cho: `boats.name`, `customers.first_name`, `customers.last_name`
- Hoặc sử dụng Elasticsearch/Meilisearch cho advanced search

### 7. **Composite Indexes Cho Common Queries** ⚠️ HIGH

**Các query pattern thường dùng cần composite indexes:**

1. **Rentals:**
   - `(boat_id, start_time, end_time, status)` → Check availability
   - `(branch_id, status, start_time)` → Branch dashboard
   - `(customer_id, status)` → Customer history
   - `(status, payment_status, start_time)` → Reports

2. **Boats:**
   - `(branch_id, status, online_bookable)` → Available boats for booking
   - `(type_id, status, available_online)` → Filter by type

3. **Invoices:**
   - `(customer_id, status)` → Customer invoices
   - `(branch_id, status, due_date)` → Overdue reports
   - `(status, due_date)` → Payment tracking

---

## ✅ Đề Xuất Tối Ưu Hóa

### Priority 1: CRITICAL (Thực hiện ngay)

1. **Thêm indexes cho bảng `rentals`**
   - Index trên `start_time`, `end_time`
   - Index trên `status`, `payment_status`
   - Composite index `(boat_id, start_time, end_time, status)`
   - Composite index `(branch_id, status, start_time)`
   - Composite index `(customer_id, status)`

2. **Thêm indexes cho bảng `boats`**
   - Index trên `status`
   - Composite index `(branch_id, status, online_bookable)`
   - Composite index `(type_id, status, available_online)`

3. **Thêm foreign key constraint cho `rentals.invoice_id`**

### Priority 2: HIGH (Thực hiện sớm)

4. **Thêm indexes cho bảng `invoices`**
   - Index trên `status`, `due_date`
   - Composite index `(customer_id, status)`
   - Composite index `(branch_id, status, due_date)`

5. **Thêm indexes cho bảng `customers`**
   - Index trên `status`, `is_active`
   - Composite index `(status, is_active)`

6. **Thêm indexes cho bảng `pricing_tiers`**
   - Composite index `(branch_id, active, sort_order)`

### Priority 3: MEDIUM (Cân nhắc)

7. **Cleanup JSON fields**
   - Migrate `prices_json` → `boat_pricing_tiers` (nếu có data)
   - Remove `addons_json` từ `rentals` (đã có `rental_addons`)

8. **Thêm indexes cho bảng `boat_images`**
   - Composite index `(boat_id, is_primary)`

### Priority 4: LOW (Tùy chọn)

9. **Soft deletes** cho các bảng quan trọng
10. **Full-text search indexes** cho search functionality
11. **Partitioning** cho bảng `rentals` nếu data lớn (theo date)

---

## 📈 Expected Performance Improvements

### Sau khi thêm indexes:

- **Rentals queries:** ⬆️ **70-90% faster**
- **Boat availability checks:** ⬆️ **80-95% faster**
- **Dashboard/reports:** ⬆️ **60-80% faster**
- **Customer history:** ⬆️ **50-70% faster**

### Storage Impact:

- **Indexes sẽ tăng storage:** ~15-25% của database size
- **Trade-off:** Worth it cho performance gains

---

## 🔧 Implementation Plan

1. ✅ Tạo migration để thêm tất cả indexes (Priority 1 & 2)
2. ⏳ Test trên staging environment
3. ⏳ Monitor query performance
4. ⏳ Cleanup JSON fields (nếu cần)
5. ⏳ Consider soft deletes (nếu cần)

---

## 📝 Notes

- Tất cả foreign keys đã có indexes tự động (Laravel behavior)
- Unique constraints đã có indexes tự động
- Cần monitor slow queries sau khi deploy
- Cân nhắc query caching cho frequently accessed data

