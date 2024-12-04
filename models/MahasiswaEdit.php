<?php

namespace PHPMaker2025\perpus2025baru;

use DI\ContainerBuilder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Cache\QueryCacheProfile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use Dflydev\FigCookies\SetCookie;
use Slim\Interfaces\RouteCollectorProxyInterface;
use Slim\App;
use League\Flysystem\DirectoryListing;
use League\Flysystem\FilesystemException;
use Closure;
use DateTime;
use DateTimeImmutable;
use DateInterval;
use Exception;
use InvalidArgumentException;

/**
 * Page class
 */
class MahasiswaEdit extends Mahasiswa
{
    use MessagesTrait;
    use FormTrait;

    // Page ID
    public string $PageID = "edit";

    // Project ID
    public string $ProjectID = PROJECT_ID;

    // Page object name
    public string $PageObjName = "MahasiswaEdit";

    // View file path
    public ?string $View = null;

    // Title
    public ?string $Title = null; // Title for <title> tag

    // CSS class/style
    public string $CurrentPageName = "mahasiswaedit";

    // Page headings
    public string $Heading = "";
    public string $Subheading = "";
    public string $PageHeader = "";
    public string $PageFooter = "";

    // Page layout
    public bool $UseLayout = true;

    // Page terminated
    private bool $terminated = false;

    // Page heading
    public function pageHeading(): string
    {
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading(): string
    {
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return Language()->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName(): string
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl(bool $withArgs = true): string
    {
        $route = GetRoute();
        $args = RemoveXss($route->getArguments());
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        return rtrim(UrlFor($route->getName(), $args), "/") . "?";
    }

    // Show Page Header
    public function showPageHeader(): void
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<div id="ew-page-header">' . $header . '</div>';
        }
    }

    // Show Page Footer
    public function showPageFooter(): void
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<div id="ew-page-footer">' . $footer . '</div>';
        }
    }

    // Set field visibility
    public function setVisibility(): void
    {
        $this->Nim->setVisibility();
        $this->Nama_Mahasiswa->setVisibility();
        $this->No_Reg_Anggota->setVisibility();
        $this->Fakultas->setVisibility();
        $this->Jurusan->setVisibility();
        $this->Program_Studi->setVisibility();
        $this->Jenjang->setVisibility();
    }

    // Constructor
    public function __construct(Language $language, AdvancedSecurity $security)
    {
        parent::__construct($language, $security);
        global $DashboardReport;
        $this->TableVar = 'mahasiswa';
        $this->TableName = 'mahasiswa';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Save if user language changed
        if (Param("language") !== null) {
            Profile()->setLanguageId(Param("language"))->saveToStorage();
        }

        // Table object (mahasiswa)
        if (!isset($GLOBALS["mahasiswa"]) || $GLOBALS["mahasiswa"]::class == PROJECT_NAMESPACE . "mahasiswa") {
            $GLOBALS["mahasiswa"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'mahasiswa');
        }

        // Open connection
        $GLOBALS["Conn"] ??= $this->getConnection();
    }

    // Is lookup
    public function isLookup(): bool
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill(): bool
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest(): bool
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup(): bool
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated(): bool
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string|bool $url URL for direction, true => show response for API
     * @return void
     */
    public function terminate(string|bool $url = ""): void
    {
        if ($this->terminated) {
            return;
        }
        global $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }
        DispatchEvent(new PageUnloadedEvent($this), PageUnloadedEvent::NAME);
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                WriteJson($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (HasJsonResponse()) { // Has JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!IsDebug() && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $pageName = GetPageName($url);
                $result = ["url" => GetUrl($url), "modal" => "1"];  // Assume return to modal for simplicity
                if (
                    SameString($pageName, GetPageName($this->getListUrl()))
                    || SameString($pageName, GetPageName($this->getViewUrl()))
                    || SameString($pageName, GetPageName(CurrentMasterTable()?->getViewUrl() ?? ""))
                ) { // List / View / Master View page
                    if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                        $result["caption"] = $this->getModalCaption($pageName);
                        $result["view"] = SameString($pageName, "mahasiswaview"); // If View page, no primary button
                    } else { // List page
                        $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    }
                } else { // Other pages (add messages and then clear messages)
                    $result = array_merge($this->getMessages(), ["modal" => "1"]);
                    $this->clearMessages();
                }
                WriteJson($result);
            } else {
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from result set
    protected function getRecordsFromResult(Result|array $result, bool $current = false): array
    {
        $rows = [];
        if ($result instanceof Result) { // Result
            while ($row = $result->fetchAssociative()) {
                $this->loadRowValues($row); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($row);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        } elseif (is_array($result)) {
            foreach ($result as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray(array $ar): array
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (isset($this->Fields[$fldname]) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (IsEmpty($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DataType::BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->uploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->uploadPath() . $file)));
                                    if (!IsEmpty($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue(array $ar): string
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['Nim'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit(): void
    {
    }

    // Lookup data
    public function lookup(array $req = [], bool $response = true): array|bool
    {
        // Get lookup object
        $fieldName = $req["field"] ?? null;
        if (!$fieldName) {
            return [];
        }
        $fld = $this->Fields[$fieldName];
        $lookup = $fld->Lookup;
        $name = $req["name"] ?? "";
        if (ContainsString($name, "query_builder_rule")) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $req["ajax"] ?? "unknown";
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $req["q"] ?? $req["sv"] ?? "";
            $pageSize = $req["n"] ?? $req["recperpage"] ?? 10;
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $req["q"] ?? "";
            $pageSize = $req["n"] ?? -1;
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $req["start"] ?? -1;
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $req["page"] ?? -1;
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($req["s"] ?? "");
        $userFilter = Decrypt($req["f"] ?? "");
        $userOrderBy = Decrypt($req["o"] ?? "");
        $keys = $req["keys"] ?? null;
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $req["v0"] ?? $req["lookupValue"] ?? "";
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $req["v" . $i] ?? "";
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, $response); // Use settings from current page
    }

    // Properties
    public string $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public bool $IsModal = false;
    public bool $IsMobileOrModal = false;
    public string $DbMasterFilter = "";
    public string $DbDetailFilter = "";
    public ?string $HashValue = null; // Hash Value
    public int $DisplayRecords = 1;
    public int $StartRecord = 0;
    public int $StopRecord = 0;
    public int $TotalRecords = 0;
    public int $RecordRange = 10;
    public int $RecordCount = 0;

    /**
     * Page run
     *
     * @return void
     */
    public function run(): void
    {
        global $ExportType, $SkipHeaderFooter;

// Is modal
        $this->IsModal = IsModal();
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param(Config("PAGE_LAYOUT"), true));

        // View
        $this->View = Get(Config("VIEW"));
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

		// Call this new function from userfn*.php file
		My_Global_Check(); // Modified by Masino Sinaga, September 10, 2023

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::NAME);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

		// Begin of Compare Root URL by Masino Sinaga, September 10, 2023
		if (MS_ALWAYS_COMPARE_ROOT_URL == TRUE) {
			if (isset($_SESSION['perpus2025baru_Root_URL'])) {
				if ($_SESSION['perpus2025baru_Root_URL'] == MS_OTHER_COMPARED_ROOT_URL && $_SESSION['perpus2025baru_Root_URL'] <> "") {
					$this->setFailureMessage(str_replace("%s", MS_OTHER_COMPARED_ROOT_URL, Container("language")->phrase("NoPermission")));
					header("Location: " . $_SESSION['perpus2025baru_Root_URL']);
				}
			}
		}
		// End of Compare Root URL by Masino Sinaga, September 10, 2023

        // Set up lookup cache
        $this->setupLookupOptions($this->Fakultas);
        $this->setupLookupOptions($this->Jurusan);
        $this->setupLookupOptions($this->Program_Studi);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;

        // Load record by position
        $loadByPosition = false;
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("Nim") ?? Key(0) ?? Route(2)) !== null) {
                $this->Nim->setQueryStringValue($keyValue);
                $this->Nim->setOldValue($this->Nim->QueryStringValue);
            } elseif (Post("Nim") !== null) {
                $this->Nim->setFormValue(Post("Nim"));
                $this->Nim->setOldValue($this->Nim->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action", "") !== "") {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey($this->getOldKey(), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("Nim") ?? Route("Nim")) !== null) {
                    $this->Nim->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->Nim->CurrentValue = null;
                }
                if (!$loadByQuery || Get(Config("TABLE_START_REC")) !== null || Get(Config("TABLE_PAGE_NUMBER")) !== null) {
                    $loadByPosition = true;
                }
            }

            // Load result set
            if ($this->isShow()) {
                if (!$this->IsModal) { // Normal edit page
                    $this->StartRecord = 1; // Initialize start position
                    $this->Result = $this->loadResult(); // Load records
                    if ($this->TotalRecords <= 0) { // No record found
                        if (!$this->peekSuccessMessage() && !$this->peekFailureMessage()) {
                            $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("mahasiswalist"); // Return to list page
                        return;
                    } elseif ($loadByPosition) { // Load record by position
                        $this->setupStartRecord(); // Set up start record position
                        // Point to current record
                        if ($this->StartRecord <= $this->TotalRecords) {
                            $this->fetch($this->StartRecord);
                            // Redirect to correct record
                            $this->loadRowValues($this->CurrentRow);
                            $url = $this->getCurrentUrl();
                            $this->terminate($url);
                            return;
                        }
                    } else { // Match key values
                        if ($this->Nim->CurrentValue != null) {
                            while ($this->fetch()) {
                                if (SameString($this->Nim->CurrentValue, $this->CurrentRow['Nim'])) {
                                    $this->setStartRecordNumber($this->StartRecord); // Save record position
                                    $loaded = true;
                                    break;
                                } else {
                                    $this->StartRecord++;
                                }
                            }
                        }
                    }

                    // Load current row values
                    if ($loaded) {
                        $this->loadRowValues($this->CurrentRow);
                    }
                } else {
                    // Load current record
                    $loaded = $this->loadRow();
                } // End modal checking
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$this->IsModal) { // Normal edit page
                    if (!$loaded) {
                        if (!$this->peekSuccessMessage() && !$this->peekFailureMessage()) {
                            $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("mahasiswalist"); // Return to list page
                        return;
                    } else {
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if (!$this->peekFailureMessage()) {
                            $this->setFailureMessage($this->language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("mahasiswalist"); // No matching record, return to list
                        return;
                    }
                } // End modal checking
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "mahasiswalist") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                if ($this->editRow()) { // Update record based on key
                    if (!$this->peekSuccessMessage()) {
                        $this->setSuccessMessage($this->language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "mahasiswalist") {
                            FlashBag()->add("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "mahasiswalist"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->terminate();
                    return;
                } elseif (($this->peekFailureMessage()[0] ?? "") == $this->language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = RowType::EDIT; // Render as Edit
        $this->resetAttributes();
        $this->renderRow();
        if (!$this->IsModal) { // Normal view page
            $this->Pager = new PrevNextPager($this, $this->StartRecord, $this->DisplayRecords, $this->TotalRecords, "", $this->RecordRange, $this->AutoHidePager, false, false);
            $this->Pager->PageNumberName = Config("TABLE_PAGE_NUMBER");
            $this->Pager->PagePhraseId = "Record"; // Show as record
        }

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            DispatchEvent(new PageRenderingEvent($this), PageRenderingEvent::NAME);

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

// Get upload files
    protected function getUploadFiles(): void
    {
    }

    // Load form values
    protected function loadFormValues(): void
    {
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Nim' before field var 'x_Nim'
        $val = $this->hasFormValue("Nim") ? $this->getFormValue("Nim") : $this->getFormValue("x_Nim");
        if (!$this->Nim->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Nim->Visible = false; // Disable update for API request
            } else {
                $this->Nim->setFormValue($val);
            }
        }
        if ($this->hasFormValue("o_Nim")) {
            $this->Nim->setOldValue($this->getFormValue("o_Nim"));
        }

        // Check field name 'Nama_Mahasiswa' before field var 'x_Nama_Mahasiswa'
        $val = $this->hasFormValue("Nama_Mahasiswa") ? $this->getFormValue("Nama_Mahasiswa") : $this->getFormValue("x_Nama_Mahasiswa");
        if (!$this->Nama_Mahasiswa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Nama_Mahasiswa->Visible = false; // Disable update for API request
            } else {
                $this->Nama_Mahasiswa->setFormValue($val);
            }
        }

        // Check field name 'No_Reg_Anggota' before field var 'x_No_Reg_Anggota'
        $val = $this->hasFormValue("No_Reg_Anggota") ? $this->getFormValue("No_Reg_Anggota") : $this->getFormValue("x_No_Reg_Anggota");
        if (!$this->No_Reg_Anggota->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->No_Reg_Anggota->Visible = false; // Disable update for API request
            } else {
                $this->No_Reg_Anggota->setFormValue($val);
            }
        }

        // Check field name 'Fakultas' before field var 'x_Fakultas'
        $val = $this->hasFormValue("Fakultas") ? $this->getFormValue("Fakultas") : $this->getFormValue("x_Fakultas");
        if (!$this->Fakultas->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Fakultas->Visible = false; // Disable update for API request
            } else {
                $this->Fakultas->setFormValue($val);
            }
        }

        // Check field name 'Jurusan' before field var 'x_Jurusan'
        $val = $this->hasFormValue("Jurusan") ? $this->getFormValue("Jurusan") : $this->getFormValue("x_Jurusan");
        if (!$this->Jurusan->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Jurusan->Visible = false; // Disable update for API request
            } else {
                $this->Jurusan->setFormValue($val);
            }
        }

        // Check field name 'Program_Studi' before field var 'x_Program_Studi'
        $val = $this->hasFormValue("Program_Studi") ? $this->getFormValue("Program_Studi") : $this->getFormValue("x_Program_Studi");
        if (!$this->Program_Studi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Program_Studi->Visible = false; // Disable update for API request
            } else {
                $this->Program_Studi->setFormValue($val);
            }
        }

        // Check field name 'Jenjang' before field var 'x_Jenjang'
        $val = $this->hasFormValue("Jenjang") ? $this->getFormValue("Jenjang") : $this->getFormValue("x_Jenjang");
        if (!$this->Jenjang->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Jenjang->Visible = false; // Disable update for API request
            } else {
                $this->Jenjang->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues(): void
    {
        $this->Nim->CurrentValue = $this->Nim->FormValue;
        $this->Nama_Mahasiswa->CurrentValue = $this->Nama_Mahasiswa->FormValue;
        $this->No_Reg_Anggota->CurrentValue = $this->No_Reg_Anggota->FormValue;
        $this->Fakultas->CurrentValue = $this->Fakultas->FormValue;
        $this->Jurusan->CurrentValue = $this->Jurusan->FormValue;
        $this->Program_Studi->CurrentValue = $this->Program_Studi->FormValue;
        $this->Jenjang->CurrentValue = $this->Jenjang->FormValue;
    }

    /**
     * Load result
     *
     * @param int $offset Offset
     * @param int $rowcnt Maximum number of rows
     * @return Result
     */
    public function loadResult(int $offset = -1, int $rowcnt = -1): Result
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load result set
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->executeQuery();
        if (property_exists($this, "TotalRecords") && $rowcnt < 0) {
            $this->TotalRecords = $result->rowCount();
            if ($this->TotalRecords <= 0) { // Handle database drivers that does not return rowCount()
                $this->TotalRecords = $this->getRecordCount($this->getListSql());
            }
        }

        // Call Records Selected event
        $this->recordsSelected($result);
        return $result;
    }

    /**
     * Load records as associative array
     *
     * @param int $offset Offset
     * @param int $rowcnt Maximum number of rows
     * @return array|bool
     */
    public function loadRows(int $offset = -1, int $rowcnt = -1): array|bool
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load result set
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->executeQuery();
        return $result->fetchAllAssociative();
    }

    /**
     * Load row based on key values
     *
     * @return bool
     */
    public function loadRow(): bool
    {
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from result set or record
     *
     * @param array|bool|null $row Record
     * @return void
     */
    public function loadRowValues(array|bool|null $row = null): void
    {
        $row = is_array($row) ? $row : $this->newRow();

        // Call Row Selected event
        $this->rowSelected($row);
        $this->Nim->setDbValue($row['Nim']);
        $this->Nama_Mahasiswa->setDbValue($row['Nama_Mahasiswa']);
        $this->No_Reg_Anggota->setDbValue($row['No_Reg_Anggota']);
        $this->Fakultas->setDbValue($row['Fakultas']);
        $this->Jurusan->setDbValue($row['Jurusan']);
        $this->Program_Studi->setDbValue($row['Program_Studi']);
        $this->Jenjang->setDbValue($row['Jenjang']);
    }

    // Return a row with default values
    protected function newRow(): array
    {
        $row = [];
        $row['Nim'] = $this->Nim->DefaultValue;
        $row['Nama_Mahasiswa'] = $this->Nama_Mahasiswa->DefaultValue;
        $row['No_Reg_Anggota'] = $this->No_Reg_Anggota->DefaultValue;
        $row['Fakultas'] = $this->Fakultas->DefaultValue;
        $row['Jurusan'] = $this->Jurusan->DefaultValue;
        $row['Program_Studi'] = $this->Program_Studi->DefaultValue;
        $row['Jenjang'] = $this->Jenjang->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord(): ?array
    {
        // Load old record
        if ($this->OldKey != "") {
            $this->setKey($this->OldKey);
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $result = ExecuteQuery($sql, $conn);
            if ($row = $result->fetchAssociative()) {
                $this->loadRowValues($row); // Load row values
                return $row;
            }
        }
        $this->loadRowValues(); // Load default row values
        return null;
    }

    // Render row values based on field settings
    public function renderRow(): void
    {
        global $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // Nim
        $this->Nim->RowCssClass = "row";

        // Nama_Mahasiswa
        $this->Nama_Mahasiswa->RowCssClass = "row";

        // No_Reg_Anggota
        $this->No_Reg_Anggota->RowCssClass = "row";

        // Fakultas
        $this->Fakultas->RowCssClass = "row";

        // Jurusan
        $this->Jurusan->RowCssClass = "row";

        // Program_Studi
        $this->Program_Studi->RowCssClass = "row";

        // Jenjang
        $this->Jenjang->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // Nim
            $this->Nim->ViewValue = $this->Nim->CurrentValue;

            // Nama_Mahasiswa
            $this->Nama_Mahasiswa->ViewValue = $this->Nama_Mahasiswa->CurrentValue;

            // No_Reg_Anggota
            $this->No_Reg_Anggota->ViewValue = $this->No_Reg_Anggota->CurrentValue;

            // Fakultas
            $curVal = strval($this->Fakultas->CurrentValue);
            if ($curVal != "") {
                $this->Fakultas->ViewValue = $this->Fakultas->lookupCacheOption($curVal);
                if ($this->Fakultas->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->Fakultas->Lookup->getTable()->Fields["id_fakulttas"]->searchExpression(), "=", $curVal, $this->Fakultas->Lookup->getTable()->Fields["id_fakulttas"]->searchDataType(), "DB");
                    $sqlWrk = $this->Fakultas->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Fakultas->Lookup->renderViewRow($rswrk[0]);
                        $this->Fakultas->ViewValue = $this->Fakultas->displayValue($arwrk);
                    } else {
                        $this->Fakultas->ViewValue = $this->Fakultas->CurrentValue;
                    }
                }
            } else {
                $this->Fakultas->ViewValue = null;
            }

            // Jurusan
            $curVal = strval($this->Jurusan->CurrentValue);
            if ($curVal != "") {
                $this->Jurusan->ViewValue = $this->Jurusan->lookupCacheOption($curVal);
                if ($this->Jurusan->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->Jurusan->Lookup->getTable()->Fields["id_jurusan"]->searchExpression(), "=", $curVal, $this->Jurusan->Lookup->getTable()->Fields["id_jurusan"]->searchDataType(), "DB");
                    $sqlWrk = $this->Jurusan->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Jurusan->Lookup->renderViewRow($rswrk[0]);
                        $this->Jurusan->ViewValue = $this->Jurusan->displayValue($arwrk);
                    } else {
                        $this->Jurusan->ViewValue = $this->Jurusan->CurrentValue;
                    }
                }
            } else {
                $this->Jurusan->ViewValue = null;
            }

            // Program_Studi
            $curVal = strval($this->Program_Studi->CurrentValue);
            if ($curVal != "") {
                $this->Program_Studi->ViewValue = $this->Program_Studi->lookupCacheOption($curVal);
                if ($this->Program_Studi->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->Program_Studi->Lookup->getTable()->Fields["id_programstudi"]->searchExpression(), "=", $curVal, $this->Program_Studi->Lookup->getTable()->Fields["id_programstudi"]->searchDataType(), "DB");
                    $sqlWrk = $this->Program_Studi->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Program_Studi->Lookup->renderViewRow($rswrk[0]);
                        $this->Program_Studi->ViewValue = $this->Program_Studi->displayValue($arwrk);
                    } else {
                        $this->Program_Studi->ViewValue = $this->Program_Studi->CurrentValue;
                    }
                }
            } else {
                $this->Program_Studi->ViewValue = null;
            }

            // Jenjang
            $this->Jenjang->ViewValue = $this->Jenjang->CurrentValue;

            // Nim
            $this->Nim->HrefValue = "";

            // Nama_Mahasiswa
            $this->Nama_Mahasiswa->HrefValue = "";

            // No_Reg_Anggota
            $this->No_Reg_Anggota->HrefValue = "";

            // Fakultas
            $this->Fakultas->HrefValue = "";

            // Jurusan
            $this->Jurusan->HrefValue = "";

            // Program_Studi
            $this->Program_Studi->HrefValue = "";

            // Jenjang
            $this->Jenjang->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // Nim
            $this->Nim->setupEditAttributes();
            if (!$this->Nim->Raw) {
                $this->Nim->CurrentValue = HtmlDecode($this->Nim->CurrentValue);
            }
            $this->Nim->EditValue = HtmlEncode($this->Nim->CurrentValue);
            $this->Nim->PlaceHolder = RemoveHtml($this->Nim->caption());

            // Nama_Mahasiswa
            $this->Nama_Mahasiswa->setupEditAttributes();
            if (!$this->Nama_Mahasiswa->Raw) {
                $this->Nama_Mahasiswa->CurrentValue = HtmlDecode($this->Nama_Mahasiswa->CurrentValue);
            }
            $this->Nama_Mahasiswa->EditValue = HtmlEncode($this->Nama_Mahasiswa->CurrentValue);
            $this->Nama_Mahasiswa->PlaceHolder = RemoveHtml($this->Nama_Mahasiswa->caption());

            // No_Reg_Anggota
            $this->No_Reg_Anggota->setupEditAttributes();
            if (!$this->No_Reg_Anggota->Raw) {
                $this->No_Reg_Anggota->CurrentValue = HtmlDecode($this->No_Reg_Anggota->CurrentValue);
            }
            $this->No_Reg_Anggota->EditValue = HtmlEncode($this->No_Reg_Anggota->CurrentValue);
            $this->No_Reg_Anggota->PlaceHolder = RemoveHtml($this->No_Reg_Anggota->caption());

            // Fakultas
            $this->Fakultas->setupEditAttributes();
            $curVal = trim(strval($this->Fakultas->CurrentValue));
            if ($curVal != "") {
                $this->Fakultas->ViewValue = $this->Fakultas->lookupCacheOption($curVal);
            } else {
                $this->Fakultas->ViewValue = $this->Fakultas->Lookup !== null && is_array($this->Fakultas->lookupOptions()) && count($this->Fakultas->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Fakultas->ViewValue !== null) { // Load from cache
                $this->Fakultas->EditValue = array_values($this->Fakultas->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Fakultas->Lookup->getTable()->Fields["id_fakulttas"]->searchExpression(), "=", $this->Fakultas->CurrentValue, $this->Fakultas->Lookup->getTable()->Fields["id_fakulttas"]->searchDataType(), "DB");
                }
                $sqlWrk = $this->Fakultas->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Fakultas->EditValue = $arwrk;
            }
            $this->Fakultas->PlaceHolder = RemoveHtml($this->Fakultas->caption());

            // Jurusan
            $this->Jurusan->setupEditAttributes();
            $curVal = trim(strval($this->Jurusan->CurrentValue));
            if ($curVal != "") {
                $this->Jurusan->ViewValue = $this->Jurusan->lookupCacheOption($curVal);
            } else {
                $this->Jurusan->ViewValue = $this->Jurusan->Lookup !== null && is_array($this->Jurusan->lookupOptions()) && count($this->Jurusan->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Jurusan->ViewValue !== null) { // Load from cache
                $this->Jurusan->EditValue = array_values($this->Jurusan->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Jurusan->Lookup->getTable()->Fields["id_jurusan"]->searchExpression(), "=", $this->Jurusan->CurrentValue, $this->Jurusan->Lookup->getTable()->Fields["id_jurusan"]->searchDataType(), "DB");
                }
                $sqlWrk = $this->Jurusan->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Jurusan->EditValue = $arwrk;
            }
            $this->Jurusan->PlaceHolder = RemoveHtml($this->Jurusan->caption());

            // Program_Studi
            $this->Program_Studi->setupEditAttributes();
            $curVal = trim(strval($this->Program_Studi->CurrentValue));
            if ($curVal != "") {
                $this->Program_Studi->ViewValue = $this->Program_Studi->lookupCacheOption($curVal);
            } else {
                $this->Program_Studi->ViewValue = $this->Program_Studi->Lookup !== null && is_array($this->Program_Studi->lookupOptions()) && count($this->Program_Studi->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Program_Studi->ViewValue !== null) { // Load from cache
                $this->Program_Studi->EditValue = array_values($this->Program_Studi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Program_Studi->Lookup->getTable()->Fields["id_programstudi"]->searchExpression(), "=", $this->Program_Studi->CurrentValue, $this->Program_Studi->Lookup->getTable()->Fields["id_programstudi"]->searchDataType(), "DB");
                }
                $sqlWrk = $this->Program_Studi->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Program_Studi->EditValue = $arwrk;
            }
            $this->Program_Studi->PlaceHolder = RemoveHtml($this->Program_Studi->caption());

            // Jenjang
            $this->Jenjang->setupEditAttributes();
            if (!$this->Jenjang->Raw) {
                $this->Jenjang->CurrentValue = HtmlDecode($this->Jenjang->CurrentValue);
            }
            $this->Jenjang->EditValue = HtmlEncode($this->Jenjang->CurrentValue);
            $this->Jenjang->PlaceHolder = RemoveHtml($this->Jenjang->caption());

            // Edit refer script

            // Nim
            $this->Nim->HrefValue = "";

            // Nama_Mahasiswa
            $this->Nama_Mahasiswa->HrefValue = "";

            // No_Reg_Anggota
            $this->No_Reg_Anggota->HrefValue = "";

            // Fakultas
            $this->Fakultas->HrefValue = "";

            // Jurusan
            $this->Jurusan->HrefValue = "";

            // Program_Studi
            $this->Program_Studi->HrefValue = "";

            // Jenjang
            $this->Jenjang->HrefValue = "";
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm(): bool
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
            if ($this->Nim->Visible && $this->Nim->Required) {
                if (!$this->Nim->IsDetailKey && IsEmpty($this->Nim->FormValue)) {
                    $this->Nim->addErrorMessage(str_replace("%s", $this->Nim->caption(), $this->Nim->RequiredErrorMessage));
                }
            }
            if ($this->Nama_Mahasiswa->Visible && $this->Nama_Mahasiswa->Required) {
                if (!$this->Nama_Mahasiswa->IsDetailKey && IsEmpty($this->Nama_Mahasiswa->FormValue)) {
                    $this->Nama_Mahasiswa->addErrorMessage(str_replace("%s", $this->Nama_Mahasiswa->caption(), $this->Nama_Mahasiswa->RequiredErrorMessage));
                }
            }
            if ($this->No_Reg_Anggota->Visible && $this->No_Reg_Anggota->Required) {
                if (!$this->No_Reg_Anggota->IsDetailKey && IsEmpty($this->No_Reg_Anggota->FormValue)) {
                    $this->No_Reg_Anggota->addErrorMessage(str_replace("%s", $this->No_Reg_Anggota->caption(), $this->No_Reg_Anggota->RequiredErrorMessage));
                }
            }
            if ($this->Fakultas->Visible && $this->Fakultas->Required) {
                if (!$this->Fakultas->IsDetailKey && IsEmpty($this->Fakultas->FormValue)) {
                    $this->Fakultas->addErrorMessage(str_replace("%s", $this->Fakultas->caption(), $this->Fakultas->RequiredErrorMessage));
                }
            }
            if ($this->Jurusan->Visible && $this->Jurusan->Required) {
                if (!$this->Jurusan->IsDetailKey && IsEmpty($this->Jurusan->FormValue)) {
                    $this->Jurusan->addErrorMessage(str_replace("%s", $this->Jurusan->caption(), $this->Jurusan->RequiredErrorMessage));
                }
            }
            if ($this->Program_Studi->Visible && $this->Program_Studi->Required) {
                if (!$this->Program_Studi->IsDetailKey && IsEmpty($this->Program_Studi->FormValue)) {
                    $this->Program_Studi->addErrorMessage(str_replace("%s", $this->Program_Studi->caption(), $this->Program_Studi->RequiredErrorMessage));
                }
            }
            if ($this->Jenjang->Visible && $this->Jenjang->Required) {
                if (!$this->Jenjang->IsDetailKey && IsEmpty($this->Jenjang->FormValue)) {
                    $this->Jenjang->addErrorMessage(str_replace("%s", $this->Jenjang->caption(), $this->Jenjang->RequiredErrorMessage));
                }
            }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Update record based on key values
    protected function editRow(): bool
    {
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $oldRow = $conn->fetchAssociative($sql);
        if (!$oldRow) {
            $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Load old values
            $this->loadDbValues($oldRow);
        }

        // Get new row
        $newRow = $this->getEditRow($oldRow);

        // Update current values
        $this->Fields->setCurrentValues($newRow);

        // Check field with unique index (Nim)
        if ($this->Nim->CurrentValue != "") {
            $filterChk = "(`Nim` = '" . AdjustSql($this->Nim->CurrentValue) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetchAssociative()) {
                $idxErrMsg = sprintf($this->language->phrase("DuplicateIndex"), $this->Nim->CurrentValue, $this->Nim->caption());
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }

        // Call Row Updating event
        $updateRow = $this->rowUpdating($oldRow, $newRow);

        // Check for duplicate key when key changed
        if ($updateRow) {
            $newKeyFilter = $this->getRecordFilter($newRow);
            if ($newKeyFilter != $oldKeyFilter) {
                $rsChk = $this->loadRs($newKeyFilter)->fetchAssociative();
                if ($rsChk !== false) {
                    $keyErrMsg = sprintf($this->language->phrase("DuplicateKey"), $newKeyFilter);
                    $this->setFailureMessage($keyErrMsg);
                    $updateRow = false;
                }
            }
        }
        if ($updateRow) {
            if (count($newRow) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($newRow, "", $oldRow);
                if (!$editRow && !IsEmpty($this->DbErrorMessage)) { // Show database error
                    $this->setFailureMessage($this->DbErrorMessage);
                }
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->peekSuccessMessage() || $this->peekFailureMessage()) {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($this->language->phrase("UpdateCancelled"));
            }
            $editRow = $updateRow;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($oldRow, $newRow);
        }

        // Write JSON response
        if (IsJsonResponse() && $editRow) {
            $row = $this->getRecordsFromResult([$newRow], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $editRow;
    }

    /**
     * Get edit row
     *
     * @return array
     */
    protected function getEditRow(array $oldRow): array
    {
        $newRow = [];

        // Nim
        $this->Nim->setDbValueDef($newRow, $this->Nim->CurrentValue, $this->Nim->ReadOnly);

        // Nama_Mahasiswa
        $this->Nama_Mahasiswa->setDbValueDef($newRow, $this->Nama_Mahasiswa->CurrentValue, $this->Nama_Mahasiswa->ReadOnly);

        // No_Reg_Anggota
        $this->No_Reg_Anggota->setDbValueDef($newRow, $this->No_Reg_Anggota->CurrentValue, $this->No_Reg_Anggota->ReadOnly);

        // Fakultas
        $this->Fakultas->setDbValueDef($newRow, $this->Fakultas->CurrentValue, $this->Fakultas->ReadOnly);

        // Jurusan
        $this->Jurusan->setDbValueDef($newRow, $this->Jurusan->CurrentValue, $this->Jurusan->ReadOnly);

        // Program_Studi
        $this->Program_Studi->setDbValueDef($newRow, $this->Program_Studi->CurrentValue, $this->Program_Studi->ReadOnly);

        // Jenjang
        $this->Jenjang->setDbValueDef($newRow, $this->Jenjang->CurrentValue, $this->Jenjang->ReadOnly);
        return $newRow;
    }

    /**
     * Restore edit form from row
     * @param array $row Row
     */
    protected function restoreEditFormFromRow(array $row): void
    {
        if (isset($row['Nim'])) { // Nim
            $this->Nim->CurrentValue = $row['Nim'];
        }
        if (isset($row['Nama_Mahasiswa'])) { // Nama_Mahasiswa
            $this->Nama_Mahasiswa->CurrentValue = $row['Nama_Mahasiswa'];
        }
        if (isset($row['No_Reg_Anggota'])) { // No_Reg_Anggota
            $this->No_Reg_Anggota->CurrentValue = $row['No_Reg_Anggota'];
        }
        if (isset($row['Fakultas'])) { // Fakultas
            $this->Fakultas->CurrentValue = $row['Fakultas'];
        }
        if (isset($row['Jurusan'])) { // Jurusan
            $this->Jurusan->CurrentValue = $row['Jurusan'];
        }
        if (isset($row['Program_Studi'])) { // Program_Studi
            $this->Program_Studi->CurrentValue = $row['Program_Studi'];
        }
        if (isset($row['Jenjang'])) { // Jenjang
            $this->Jenjang->CurrentValue = $row['Jenjang'];
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb(): void
    {
        $breadcrumb = Breadcrumb();
        $url = CurrentUrl();
        $breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("mahasiswalist"), "", $this->TableVar, true);
        $pageId = "edit";
        $breadcrumb->add("edit", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions(DbField $fld): void
    {
        if ($fld->Lookup && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_Fakultas":
                    break;
                case "x_Jurusan":
                    break;
                case "x_Program_Studi":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $qb = $fld->Lookup->getSqlAsQueryBuilder(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $qb != null && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($qb, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($qb->getSQL())->fetchAllAssociative();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Set up starting record parameters
    public function setupStartRecord(): void
    {
        $pagerTable = Get(Config("TABLE_PAGER_TABLE_NAME"));
        if ($this->DisplayRecords == 0 || $pagerTable && $pagerTable != $this->TableVar) { // Display all records / Check if paging for this table
            return;
        }
        $pageNo = Get(Config("TABLE_PAGE_NUMBER"));
        $startRec = Get(Config("TABLE_START_REC"));
        $infiniteScroll = false;
        $recordNo = $pageNo ?? $startRec; // Record number = page number or start record
        if ($recordNo !== null && is_numeric($recordNo)) {
            $this->StartRecord = $recordNo;
        } else {
            $this->StartRecord = $this->getStartRecordNumber();
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount(): int
    {
        return ceil($this->TotalRecords / $this->DisplayRecords);
    }

    // Page Load event
    public function pageLoad(): void
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload(): void
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(string &$url): void
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(string &$message, string $type): void
    {
        if ($type == "success") {
            //$message = "your success message";
        } elseif ($type == "failure") {
            //$message = "your failure message";
        } elseif ($type == "warning") {
            //$message = "your warning message";
        } else {
            //$message = "your message";
        }
    }

    // Page Render event
    public function pageRender(): void
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(string &$header): void
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(string &$footer): void
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(bool &$break, string &$content): void
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }

    // Form Custom Validate event
    public function formCustomValidate(string &$customError): bool
    {
        // Return error message in $customError
        return true;
    }
}
