{"title":"\u0e41\u0e01\u0e49\u0e44\u0e02 Remote Desktop Authentication Error Has Occurred","icon":"fas fa-unlink","date":"2021-09-02 01:15:36"}//START_HERE//#### วิธีแก้ไข Remote Desktop Authentication Error Has Occurred

![](https://controlpanel.craft.in.th/assets/image/acc1.png)

##### สามารถแก้ไขทั้งใน Windows 7 / Windows 8.1 และ Windows 10.

1 กด Windows + R และทำการพิมพ์ gpedit.msc และกด OK

![](https://controlpanel.craft.in.th/assets/image/acc2.jpg)

2 ไปที่ Computer Configuration > Administrative Templates > System > Credentials delegation > เลือก Encryption Oracle Remediation
ปรับให้เป็น Enable > เลือก Vulnerable  และกด Ok

![](https://controlpanel.craft.in.th/assets/image/acc3.jpg)

3 เรียบร้อย จากนั้นให้เราเข้า Remote Desktop ใหม่ ก็สามารถทำการ remote ได้เรียบร้อยครับ

