public class copy_loi {
    public static void copyRecords(){
        List<TrackIT__Inv_Location__c> origLoi = [
            Select 
                Id, Max_Storage_Capacity__c, Optimal_Quantity__c, Reserved_Quantity__c, Restock_Point__c, Store_Location__c, Temporary_Location__c, TrackIT__Inventory__c, TrackIT__Location__c, TrackIT__Location_Name__c, TrackIT__Quantity__c, TrackIT__Target_Location_Desc__c 
            from TrackIT__Inv_Location__c WHERE isDeleted = FALSE AND TrackIT__Location__r.Id ='a3W1U000000it28UAA'
        ];
        List<TrackIT__Inv_Location__c> newLoiList = New List<TrackIT__Inv_Location__c>();
        for(TrackIT__Inv_Location__c loi : origLoi){
            TrackIT__Inv_Location__c tmpLoi = New TrackIT__Inv_Location__c();
            tmpLoi.Max_Storage_Capacity__c = loi.Max_Storage_Capacity__c;
            tmpLoi.Optimal_Quantity__c = loi.Optimal_Quantity__c;
            tmpLoi.Reserved_Quantity__c = loi.Reserved_Quantity__c;
            tmpLoi.Restock_Point__c = loi.Restock_Point__c;
            tmpLoi.Store_Location__c = loi.Store_Location__c;
            tmpLoi.Temporary_Location__c = loi.Temporary_Location__c;
            tmpLoi.TrackIT__Inventory__c = loi.TrackIT__Inventory__c;
            tmpLoi.TrackIT__Location__c = 'a3W1U000000S4YVUA0';
            tmpLoi.TrackIT__Location_Name__c = 'TR022 White F350 Utility - B. Site Concrete';
            tmpLoi.TrackIT__Quantity__c = loi.TrackIT__Quantity__c;
            tmpLoi.TrackIT__Target_Location_Desc__c  = '';
            newLoiList.add(tmpLoi);
        } 
        Insert newLoiList;
    }
}
