<?php 
	error_reporting(0);
	// Query first site record
	$qrySTE = $link->query("SELECT * FROM sites LIMIT 1");
	if ($qrySTE && $qrySTE->num_rows > 0) {
		$arySTE = $qrySTE->fetch_assoc();
		$sidSTE = $arySTE['sid'];         // Use column name instead of index
		$munSTE = $arySTE['mcode'];
		$barSTE = $arySTE['barangay'];
	} else {
		// Handle case where no records are found
		$sidSTE = $munSTE = $barSTE = null;
	}
	
	// Query the first municipality
	$qryMUN = $link->query("SELECT * FROM municipality LIMIT 1");
	if ($qryMUN && $qryMUN->num_rows > 0) {
		$aryMUN = $qryMUN->fetch_assoc();
		$codMUN = $aryMUN["mcode"];
		$namMUN = $aryMUN["mname"];
	} else {
		$codMUN = $namMUN = null; // fallback if no data
	}

	$qryBAR = $link->query("SELECT * FROM barangays");
	$barangays = [];
	if ($qryBAR && $qryBAR->num_rows > 0) {
		while ($row = $qryBAR->fetch_assoc()) {
			$barangays[] = [
				'bid' => $row['bid'],
				'mcode' => $row['mcode'],
				'barangay' => $row['barangay']
			];
		}
	}

	// Count all barangays
	$qraBAR = $link->query("SELECT COUNT(bid) AS total FROM barangays");
	if ($qraBAR && $qraBAR->num_rows > 0) {
		$araBAR = $qraBAR->fetch_assoc();
		$allBAR = number_format($araBAR['total']);
	} else { $allBAR = 0; }

	// Count all municipalities
	$qraMUN = $link->query("SELECT COUNT(mid) AS total FROM municipality");
	if ($qraMUN && $qraMUN->num_rows > 0) {
		$araMUN = $qraMUN->fetch_assoc();
		$allMUN = number_format($araMUN['total']);
	} else { $allMUN = 0; }

	// Count municipalities with at least one site installed
	$qrcMUN = $link->query("SELECT COUNT(DISTINCT mcode) AS total FROM sites");
	if ($qrcMUN && $qrcMUN->num_rows > 0) {
		$cntMUN = $qrcMUN->fetch_assoc();
		$totMUN = number_format($cntMUN['total']);
	} else { $totMUN = 0; }

	// Count barangays with installations (WIN = 'Yes') across all municipalities
	$qrcBAR = $link->query("SELECT COUNT(bid) AS total FROM barangays WHERE win = 'Yes'");
	if ($qrcBAR && $qrcBAR->num_rows > 0) {
		$cntBAR = $qrcBAR->fetch_assoc();
		$totBAR = number_format($cntBAR['total']);
	} else { $totBAR = 0; }

	// Count barangays with installations (WIN = 'Yes') for a specific municipality
	$qrcWIN = $link->query("SELECT COUNT(bid) AS total FROM barangays WHERE win = 'Yes' AND mcode = '$mcode'");
	if ($qrcWIN && $qrcWIN->num_rows > 0) {
		$cntWIN = $qrcWIN->fetch_assoc();
		$totWIN = number_format($cntWIN['total']);
	} else { $totWIN = 0; }

	// Count Barangay Halls (place = 'BAP')
	$qrcBAP = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'BAP'");
	if ($qrcBAP && $qrcBAP->num_rows > 0) {
		$cntBAP = $qrcBAP->fetch_assoc();
		$totBAP = number_format($cntBAP['total']);
	} else { $totBAP = 0; }

	// Count Barangay Health Centers (place = 'BHC')
	$qrcBHC = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'BHC'");
	if ($qrcBHC && $qrcBHC->num_rows > 0) {
		$cntBHC = $qrcBHC->fetch_assoc();
		$totBHC = number_format($cntBHC['total']);
	} else { $totBHC = 0; }
	
	// Count Base Stations (place = 'BST')
	$qrcBST = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'BST'");
	if ($qrcBST && $qrcBST->num_rows > 0) {
		$cntBST = $qrcBST->fetch_assoc();
		$totBST = number_format($cntBST['total']);
	} else { $totBST = 0; }
	
	// Count Coast Guard Posts (place = 'CGD')
	$qrcCGD = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'CGD'");
	if ($qrcCGD && $qrcCGD->num_rows > 0) {
		$cntCGD = $qrcCGD->fetch_assoc();
		$totCGD = number_format($cntCGD['total']);
	} else { $totCGD = 0; }

	// Count Check Points (place = 'CKP')
	$qrcCKP = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'CKP'");
	if ($qrcCKP && $qrcCKP->num_rows > 0) {
		$cntCKP = $qrcCKP->fetch_assoc();
		$totCKP = number_format($cntCKP['total']);
	} else { $totCKP = 0; }

	// Count Military Camps (place = 'CMP')
	$qrcCMP = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'CMP'");
	if ($qrcCMP && $qrcCMP->num_rows > 0) {
		$cntCMP = $qrcCMP->fetch_assoc();
		$totCMP = number_format($cntCMP['total']);
	} else { $totCMP = 0; }

	// Count Army Detachments (place = 'DET')
	$qrcDET = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'DET'");
	if ($qrcDET && $qrcDET->num_rows > 0) {
		$cntDET = $qrcDET->fetch_assoc();
		$totDET = number_format($cntDET['total']);
	} else { $totDET = 0; }

	// Count Elementary Schools (place = 'ELS')
	$qrcELS = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'ELS'");
	if ($qrcELS && $qrcELS->num_rows > 0) {
		$cntELS = $qrcELS->fetch_assoc();
		$totELS = number_format($cntELS['total']);
	} else { $totELS = 0; }

	// Count Elementary Schools 2 (place = 'ES2')
	$qrcES2 = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'ES2'");
	if ($qrcES2 && $qrcES2->num_rows > 0) {
		$cntES2 = $qrcES2->fetch_assoc();
		$totES2 = number_format($cntES2['total']);
	} else { $totES2 = 0; }

	// Count Other Public Places (place = 'ETC')
	$qrcETC = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'ETC'");
	if ($qrcETC && $qrcETC->num_rows > 0) {
		$cntETC = $qrcETC->fetch_assoc();
		$totETC = number_format($cntETC['total']);
	} else { $totETC = 0; }

	// Count Government Offices (place = 'GOF')
	$qrcGOF = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'GOF'");
	if ($qrcGOF && $qrcGOF->num_rows > 0) {
		$cntGOF = $qrcGOF->fetch_assoc();
		$totGOF = number_format($cntGOF['total']);
	} else { $totGOF = 0; }

	// Count Gymnasiums (place = 'GYM')
	$qrcGYM = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'GYM'");
	if ($qrcGYM && $qrcGYM->num_rows > 0) {
		$cntGYM = $qrcGYM->fetch_assoc();
		$totGYM = number_format($cntGYM['total']);
	} else { $totGYM = 0; }

	// Count Hospitals (place = 'HOS')
	$qrcHOS = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'HOS'");
	if ($qrcHOS && $qrcHOS->num_rows > 0) {
		$cntHOS = $qrcHOS->fetch_assoc();
		$totHOS = number_format($cntHOS['total']);
	} else { $totHOS = 0; }

	// Count Integrated Schools (place = 'INT')
	$qrcINT = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'INT'");
	if ($qrcINT && $qrcINT->num_rows > 0) {
		$cntINT = $qrcINT->fetch_assoc();
		$totINT = number_format($cntINT['total']);
	} else { $totINT = 0; }

	// Count Municipal Halls (place = 'MHL')
	$qrcMHL = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'MHL'");
	if ($qrcMHL && $qrcMHL->num_rows > 0) {
		$cntMHL = $qrcMHL->fetch_assoc();
		$totMHL = number_format($cntMHL['total']);
	} else { $totMHL = 0; }

	// Count National High Schools (place = 'NHS')
	$qrcNHS = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'NHS'");
	if ($qrcNHS && $qrcNHS->num_rows > 0) {
		$cntNHS = $qrcNHS->fetch_assoc();
		$totNHS = number_format($cntNHS['total']);
	} else { $totNHS = 0; }

	// Count Provincial Government Colleges (place = 'PGC')
	$qrcPGC = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'PGC'");
	if ($qrcPGC && $qrcPGC->num_rows > 0) {
		$cntPGC = $qrcPGC->fetch_assoc();
		$totPGC = number_format($cntPGC['total']);
	} else { $totPGC = 0; }

	// Count Public Markets (place = 'PMA')
	$qrcPMA = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'PMA'");
	if ($qrcPMA && $qrcPMA->num_rows > 0) {
		$cntPMA = $qrcPMA->fetch_assoc();
		$totPMA = number_format($cntPMA['total']);
	} else { $totPMA = 0; }

	// Count Relay Stations (place = 'REL')
	$qrcREL = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'REL'");
	if ($qrcREL && $qrcREL->num_rows > 0) {
		$cntREL = $qrcREL->fetch_assoc();
		$totREL = number_format($cntREL['total']);
	} else { $totREL = 0; }

	// Count Recreational/Resort Places (place = 'RES')
	$qrcRES = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'RES'");
	if ($qrcRES && $qrcRES->num_rows > 0) {
		$cntRES = $qrcRES->fetch_assoc();
		$totRES = number_format($cntRES['total']);
	} else { $totRES = 0; }

	// Count SSFO Offices (place = 'SFO')
	$qrcSFO = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'SFO'");
	if ($qrcSFO && $qrcSFO->num_rows > 0) {
		$cntSFO = $qrcSFO->fetch_assoc();
		$totSFO = number_format($cntSFO['total']);
	} else { $totSFO = 0; }

	// Count Senior High Schools (place = 'SHS')
	$qrcSHS = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'SHS'");
	if ($qrcSHS && $qrcSHS->num_rows > 0) {
		$cntSHS = $qrcSHS->fetch_assoc();
		$totSHS = number_format($cntSHS['total']);
	} else { $totSHS = 0; }

	// Count Public Ports and Terminals (place = 'TER')
	$qrcTER = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'TER'");
	if ($qrcTER && $qrcTER->num_rows > 0) {
		$cntTER = $qrcTER->fetch_assoc();
		$totTER = number_format($cntTER['total']);
	} else { $totTER = 0; }

	// Count Vocational and Technical Centers (place = 'VOC')
	$qrcVOC = $link->query("SELECT COUNT(place) AS total FROM sites WHERE place = 'VOC'");
	if ($qrcVOC && $qrcVOC->num_rows > 0) {
		$cntVOC = $qrcVOC->fetch_assoc();
		$totVOC = number_format($cntVOC['total']);
	} else { $totVOC = 0; }

	$totSCH = $totELS+$totES2+$totNHS+$totVOC+$totINT+$totPGC+$totINT;
	$totBMH = $totBAP+$totMHL+$totBHC+$totGOF+$totHOS+$totPMA+$totSFO+$totTER+$totGYM;
	$totOTH = $totBST+$totCGD+$totCKP+$totCMP+$totDET+$totETC+$totREL+$totRES+1;
	
	$totALL=$totBMH+$totSCH+$totOTH;	

	//Number of Installed WiFi in Schools per Municipality
	$cntES = $link->query("select count(place) from sites where mcode='$mcode' and place='ELS' ") or die(mysqli_error($link));
	$aryES = mysqli_fetch_array($cntES);	
	$totES = number_format($aryES[0]);

	$cntE2 = $link->query("select count(place) from sites where mcode='$mcode' and place='ES2' ") or die(mysqli_error($link));
	$aryE2 = mysqli_fetch_array($cntE2);	
	$totE2 = number_format($aryE2[0]);

	$cntHS = $link->query("select count(place) from sites where mcode='$mcode' and place='NHS' ") or die(mysqli_error($link));
	$aryHS = mysqli_fetch_array($cntHS);	
	$totHS = number_format($aryHS[0]);

	$cntSH = $link->query("select count(place) from sites where mcode='$mcode' and place='SHS' ") or die(mysqli_error($link));
	$arySH = mysqli_fetch_array($cntSH);	
	$totSH = number_format($arySH[0]);

	$cntIS = $link->query("select count(place) from sites where mcode='$mcode' and place='INT' ") or die(mysqli_error($link));
	$aryIS = mysqli_fetch_array($cntIS);	
	$totIS = number_format($aryIS[0]);

	$cntVS = $link->query("select count(place) from sites where mcode='$mcode' and place='VOC' ") or die(mysqli_error($link));
	$aryVS = mysqli_fetch_array($cntVS);	
	$totVS = number_format($aryVS[0]);

	$cntGC = $link->query("select count(place) from sites where mcode='$mcode' and place='PGC' ") or die(mysqli_error($link));
	$aryGC = mysqli_fetch_array($cntGC);	
	$totGC = number_format($aryGC[0]);
	
	//Number of Installed WiFi in Barangay Centers per Municipality
	$cntBH = $link->query("select count(place) from sites where mcode='$mcode' and place='BAP' ") or die(mysqli_error($link));
	$aryBH = mysqli_fetch_array($cntBH);	
	$totBH = number_format($aryBH[0]);

	$cntBC = $link->query("select count(place) from sites where mcode='$mcode' and place='BHC' ") or die(mysqli_error($link));
	$aryBC = mysqli_fetch_array($cntBC);	
	$totBC = number_format($aryBC[0]);

	$cntMH = $link->query("select count(place) from sites where mcode='$mcode' and place='MHL' ") or die(mysqli_error($link));
	$aryMH = mysqli_fetch_array($cntMH);	
	$totMH = number_format($aryMH[0]);

	$cntPM = $link->query("select count(place) from sites where mcode='$mcode' and place='PMA' ") or die(mysqli_error($link));
	$aryPM = mysqli_fetch_array($cntPM);	
	$totPM = number_format($aryPM[0]);

	$cntPT = $link->query("select count(place) from sites where mcode='$mcode' and place='TER' ") or die(mysqli_error($link));
	$aryPT = mysqli_fetch_array($cntPT);	
	$totPT = number_format($aryPT[0]);

	$cntGM = $link->query("select count(place) from sites where mcode='$mcode' and place='GYM' ") or die(mysqli_error($link));
	$aryGM = mysqli_fetch_array($cntGM);	
	$totGM = number_format($aryGM[0]);

	$cntGO = $link->query("select count(place) from sites where mcode='$mcode' and place='GOF' ") or die(mysqli_error($link));
	$aryGO = mysqli_fetch_array($cntGO);	
	$totGO = number_format($aryGO[0]);

	$cntHO = $link->query("select count(place) from sites where mcode='$mcode' and place='HOS' ") or die(mysqli_error($link));
	$aryHO = mysqli_fetch_array($cntHO);	
	$totHO = number_format($aryHO[0]);

	$cntSF = $link->query("select count(place) from sites where mcode='$mcode' and place='SFO' ") or die(mysqli_error($link));
	$arySF = mysqli_fetch_array($cntSF);	
	$totSF = number_format($arySF[0]);

	//Number of Installed WiFi in Other Public Places per Municipality
	$cntBS = $link->query("select count(place) from sites where mcode='$mcode' and place='BST' ") or die(mysqli_error($link));
	$aryBS = mysqli_fetch_array($cntBS);	
	$totBS = number_format($aryBS[0]);

	$cntCG = $link->query("select count(place) from sites where mcode='$mcode' and place='CDG' ") or die(mysqli_error($link));
	$aryCG = mysqli_fetch_array($cntCG);	
	$totCG = number_format($aryCG[0]);

	$cntCP = $link->query("select count(place) from sites where mcode='$mcode' and place='CKP' ") or die(mysqli_error($link));
	$aryCP = mysqli_fetch_array($cntCP);	
	$totCP = number_format($aryCP[0]);

	$cntMC = $link->query("select count(place) from sites where mcode='$mcode' and place='CMP' ") or die(mysqli_error($link));
	$aryMC = mysqli_fetch_array($cntMC);	
	$totMC = number_format($aryMC[0]);

	$cntDC = $link->query("select count(place) from sites where mcode='$mcode' and place='DET' ") or die(mysqli_error($link));
	$aryDC = mysqli_fetch_array($cntDC);	
	$totDC = number_format($aryDC[0]);

	$cntOP = $link->query("select count(place) from sites where mcode='$mcode' and place='ETC' ") or die(mysqli_error($link));
	$aryOP = mysqli_fetch_array($cntOP);	
	$totOP = number_format($aryOP[0]);

	$cntRL = $link->query("select count(place) from sites where mcode='$mcode' and place='REL' ") or die(mysqli_error($link));
	$aryRL = mysqli_fetch_array($cntRL);	
	$totRL = number_format($aryRL[0]);

	$cntRC = $link->query("select count(place) from sites where mcode='$mcode' and place='RES' ") or die(mysqli_error($link));
	$aryRC = mysqli_fetch_array($cntRC);	
	$totRC = number_format($aryRC[0]);

	//TOTALS
	$totBPM = $totBH+$totBC+$totMH+$totPM+$totPT+$totGM+$totGO+$totHO+$totSF;
	$totSPM = $totES+$totE2+$totHS+$totSH+$totIS+$totVS+$totGC;
	$totOPM = $totBS+$totCG+$totCP+$totMC+$totDC+$totOP+$totRL+$totRC;
	
	//Total Number of Barangays per Municipalities
	$cntBar = $link->query("select count(barangay) from barangays where mcode = '$mcode' ") or die(mysqli_error($link));
	$aryBar = mysqli_fetch_array($cntBar);	
	$totBar = number_format($aryBar[0]);
	
	//Total Number of Installed WiFi per Municipality
	$cntwFi = $link->query("select count(barangay) from sites where mcode = '$mcode' ") or die(mysqli_error($link));
	$arywFi = mysqli_fetch_array($cntwFi);	
	$totwFi = number_format($arywFi[0]);

	//COUNT DEPLOYED DEVICES
	//Count Deployed Access Points Devices (APS)
	$qraAPS=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Access Point'");
	$araAPS=mysqli_fetch_array($qraAPS);	
	$allAPS=number_format($araAPS[0]);
	
	//Count Deployed Networking Devices (NET)
	$qraNET=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Networking'");
	$araNET=mysqli_fetch_array($qraNET);	
	$allNET=number_format($araNET[0]);
	
	//Count Deployed Routers Devices (ROU)
	$qraROU=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Router'");
	$araROU=mysqli_fetch_array($qraROU);	
	$allROU=number_format($araROU[0]);

	//Count Deployed Switches Devices (USW)
	$qraUSW=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Switch'");
	$araUSW=mysqli_fetch_array($qraUSW);	
	$allUSW=number_format($araUSW[0]);

	//Count Deployed Antenna Devices (ANT)
	$qraANT=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Antenna'");
	$araANT=mysqli_fetch_array($qraANT);	
	$allANT=number_format($araANT[0]);

	//Count Deployed Radio Only Devices (RDO)
	$qraRDO=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Radio Only'");
	$araRDO=mysqli_fetch_array($qraRDO);	
	$allRDO=number_format($araRDO[0]);
	
	//Count Deployed Radio Builtin Devices (RDB)
	$qraRDB=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Radio Builtin'");
	$araRDB=mysqli_fetch_array($qraRDB);	
	$allRDB=number_format($araRDB[0]);	

	//Count Deployed Hardware Devices (OTH)
	$qraHRD=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Hardware'");
	$araHRD=mysqli_fetch_array($qraHRD);	
	$allHRD=number_format($araHRD[0]);

	//Count Deployed Electrical Devices (OTH)
	$qraELE=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Electrical'");
	$araELE=mysqli_fetch_array($qraELE);	
	$allELE=number_format($araELE[0]);
	
	//Count Deployed Other Devices (OTH)
	$qraOTD=$link->query("SELECT COUNT(device_id) FROM sites_detail where device_category='Others'");
	$araOTD=mysqli_fetch_array($qraOTD);	
	$allOTD=number_format($araOTD[0]);		
	
	$totDEV = $allAPS+$allNET+$allROU+$allUSW+$allANT+$allRDO+$allRDB+$allHRD+$allELE+$allOTD;
	
	$allNRS = $allNET+$allROU+$allUSW;
	$allRAD = $allANT+$allRDO+$allRDB;
	$allOTH = $allHRD+$allELE+$allOTD;
?>