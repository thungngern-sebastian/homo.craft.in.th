<?php class L {
const home = 'Home';
const deploy = 'All Service';
const register = 'Register';
const login = 'Login';
const email = 'Email';
const password = 'Password';
const confpassword = 'Confirm Password';
const Iagree = 'I agree';
const Term = 'Term and condition';
const hasacc = 'Already has account';
const didnthavacc = 'Didn\'t have account';
const forget = 'Forget password';
const fname = 'First name';
const lname = 'Last name';
const sendrest = 'Send reset email';
const email_exist = 'This email exist';
const complete = 'Complete';
const data_incorrect = 'Data incorrect';
const balance = 'Balance';
const topup = 'Topup';
const paymentoption = 'Payment Option';
const cloudvps = 'Cloud';
const lenght = 'Length';
const day = 'Day';
const sat = 'Week';
const month = 'Month';
const image = 'Image';
const selectversion = 'Select Version';
const plan = 'Plan';
const package = 'Package';
const custom = 'Custom';
const core = 'Core';
const ram = 'Ram';
const disk = 'Disk';
const option = 'Option';
const vmnameoption = 'Name (If leave blank, the system will randomly name)';
const vmnameonly = 'Applicable only A-Z a-z. - _! @.';
const lowest = 'Lowest';
const highest = 'Highest';
const autosystem = 'Auto System';
const noautosystem = 'Manual System';
const supportallbank = 'Supports all banks that can transfer to Kasikorn-Bank';
const pnttwtdacuabtti = 'Please notify the transfer within the day and can use another bank to transfer in.';
const transferhour = 'Transfer Hour';
const transferminute = 'Transfer Minute';
const amountlimit = 'Amount (baht currency, minimum 20 baht, maximum 150,000 baht if not eligible Considered void And will not refund)';
const summary = 'Summary';
const summarytype = 'Type';
const summaryamount = 'Amount';
const orderhistory = 'Order History';
const topuphistory = 'Topup History';
const setting = 'Setting';
const logout = 'Logout';
const info = 'Info';
const price = 'Price';
const date = 'Date';
const showing = 'Showing';
const to = 'To';
const of = 'Of';
const rows = 'Rows';
const search = 'Search';
const profile = 'Profile';
const idcard = 'ID card Verification';
const thaiid = 'Thai Id Card';
const provide = 'Provide';
const verify = 'Verify';
const changepassword = 'Change Password';
const oldpassword = 'Old Password';
const newpassword = 'New Password';
const confirmpassword = 'Confirm Password';
const article = 'Article';
const allservice = 'All Service';
const cloudserver = 'Virtual Machine Cloud';
const dedicated = 'Dedicated Server';
const colocation = 'Co-Location Service';
const service = 'Service';
const term = 'Terms and conditions of policy and usage';
const contact = 'Contact us';
const address = '1848/8 Soi Phra Mae Mary, Chan 27 Road Thung Wat Don Subdistrict, Sathon District, Bangkok 10120';
const overview = 'Overview';
const online = 'Online';
const cpu = 'CPU';
const diskall = 'Storage';
const os = 'Opening System';
const distro = 'Distro';
const exprirationdate = 'Expiration date';
const shutdown = 'Shutdown';
const restart = 'Reboot';
const forceshutdown = 'Force Shutdown';
const forcerestart = 'Force Restart';
const renew = 'Renew';
const performance = 'Performance Monitor';
const networkdownload = 'Network Download';
const networkupload = 'Network Upload';
const newmachinename = 'New Virtual-Machine Name';
const resetip = 'Reset IP';
const cannotaccess = 'When you do not get into Virtual-Machine Restart and then still must Shutdown.';
const cannotuse = 'When you think your Virtual-Machine is no longer able to be used * Must Shutdown';
const deletethiscloud = 'Delete This Cloud';
const deletevm = 'If this Virtual-Machine is deleted, data cannot be recovered.';
const delete = 'Delete';
const reset = 'Reset OS';
const stack = 'Stack';
const cookie = 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.';
const accept = 'Accept';
const homeintro = 'Cloud network service, high speed connection for business enterprise server Both in the country and abroad fully.';
const welcomedrite1 = 'Welcome to the';
const welcomedrite2 = 'DriteStudio Cloud Server';
const noserver1 = 'Oh Sorry! You don\'t have Virtual-Machine running.';
const noserver2 = 'Please rent the Virtual-Machine before making the transaction.';
const nohosting1 = 'Oh Sorry! You don\'t have Hosting running.';
const nohosting2 = 'Please rent the Hosting before making the transaction.';
const regismin = 'Rent in a few minute';
const helps = 'Help';
const hosting = 'Hosting';
const useengname = 'Please use first name-last name English only';
const help_center = 'Help';
const rentcloudbtn = 'Rent a Cloud';
const dmhead = 'Display Preference';
const dmbtn = 'On/Off Dark Mode';
const publicipadd = 'Added public IP';
const digji = 'add domestic internet';
const migi = 'Add 8GB RAM';
const fjjs = 'contact us';
const fkjds = 'notice of payment';
const Delete_User = 'Delete Account';
const Delete_User_Comfirm = 'Confirm Delete Account';
public static function __callStatic($string, $args) {
    return vsprintf(constant("self::" . $string), $args);
}
}
function L($string, $args=NULL) {
    $return = constant("L::".$string);
    return $args ? vsprintf($return,$args) : $return;
}