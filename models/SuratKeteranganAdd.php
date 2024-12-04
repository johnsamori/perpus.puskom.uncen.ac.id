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
class SuratKeteranganAdd extends SuratKeterangan
{
    use MessagesTrait;
    use FormTrait;

    // Page ID
    public string $PageID = "add";

    // Project ID
    public string $ProjectID = PROJECT_ID;

    // Page object name
    public string $PageObjName = "SuratKeteranganAdd";

    // View file path
    public ?string $View = null;

    // Title
    public ?string $Title = null; // Title for <title> tag

    // CSS class/style
    public string $CurrentPageName = "suratketeranganadd";

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
        $this->Nomor_Surat->setVisibility();
        $this->Tanggal_Surat->setVisibility();
        $this->Nama_Mahasiswa->setVisibility();
        $this->Pejabat->setVisibility();
        $this->Arsip_Surat->setVisibility();
    }

    // Constructor
    public function __construct(Language $language, AdvancedSecurity $security)
    {
        parent::__construct($language, $security);
        global $DashboardReport;
        $this->TableVar = 'surat_keterangan';
        $this->TableName = 'surat_keterangan';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Save if user language changed
        if (Param("language") !== null) {
            Profile()->setLanguageId(Param("language"))->saveToStorage();
        }

        // Table object (surat_keterangan)
        if (!isset($GLOBALS["surat_keterangan"]) || $GLOBALS["surat_keterangan"]::class == PROJECT_NAMESPACE . "surat_keterangan") {
            $GLOBALS["surat_keterangan"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'surat_keterangan');
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
                        $result["view"] = SameString($pageName, "suratketeranganview"); // If View page, no primary button
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
            $key .= @$ar['Nomor_Surat'];
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
    public string $FormClassName = "ew-form ew-add-form";
    public bool $IsModal = false;
    public bool $IsMobileOrModal = false;
    public string $DbMasterFilter = "";
    public string $DbDetailFilter = "";
    public int $StartRecord = 0;
    public int $Priv = 0;
    public bool $CopyRecord = false;

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
        $this->setupLookupOptions($this->Nama_Mahasiswa);
        $this->setupLookupOptions($this->Pejabat);

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey($this->getOldKey());
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("Nomor_Surat") ?? Route("Nomor_Surat")) !== null) {
                $this->Nomor_Surat->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !IsEmpty($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $oldRow = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$oldRow) { // Record not loaded
                    if (!$this->peekFailureMessage()) {
                        $this->setFailureMessage($this->language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("suratketeranganlist"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                if ($this->addRow($oldRow)) { // Add successful
                    if (!$this->peekSuccessMessage() && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($this->language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "suratketeranganlist") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "suratketeranganview") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "suratketeranganlist") {
                            FlashBag()->add("Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "suratketeranganlist"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    WriteJson(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = RowType::ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

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
        $this->Arsip_Surat->Upload->Index = $this->FormIndex;
        $this->Arsip_Surat->Upload->uploadFile();
        $this->Arsip_Surat->CurrentValue = $this->Arsip_Surat->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues(): void
    {
    }

    // Load form values
    protected function loadFormValues(): void
    {
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'Nomor_Surat' before field var 'x_Nomor_Surat'
        $val = $this->hasFormValue("Nomor_Surat") ? $this->getFormValue("Nomor_Surat") : $this->getFormValue("x_Nomor_Surat");
        if (!$this->Nomor_Surat->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Nomor_Surat->Visible = false; // Disable update for API request
            } else {
                $this->Nomor_Surat->setFormValue($val);
            }
        }

        // Check field name 'Tanggal_Surat' before field var 'x_Tanggal_Surat'
        $val = $this->hasFormValue("Tanggal_Surat") ? $this->getFormValue("Tanggal_Surat") : $this->getFormValue("x_Tanggal_Surat");
        if (!$this->Tanggal_Surat->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Tanggal_Surat->Visible = false; // Disable update for API request
            } else {
                $this->Tanggal_Surat->setFormValue($val, true, $validate);
            }
            $this->Tanggal_Surat->CurrentValue = UnformatDateTime($this->Tanggal_Surat->CurrentValue, $this->Tanggal_Surat->formatPattern());
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

        // Check field name 'Pejabat' before field var 'x_Pejabat'
        $val = $this->hasFormValue("Pejabat") ? $this->getFormValue("Pejabat") : $this->getFormValue("x_Pejabat");
        if (!$this->Pejabat->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->Pejabat->Visible = false; // Disable update for API request
            } else {
                $this->Pejabat->setFormValue($val);
            }
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues(): void
    {
        $this->Nomor_Surat->CurrentValue = $this->Nomor_Surat->FormValue;
        $this->Tanggal_Surat->CurrentValue = $this->Tanggal_Surat->FormValue;
        $this->Tanggal_Surat->CurrentValue = UnformatDateTime($this->Tanggal_Surat->CurrentValue, $this->Tanggal_Surat->formatPattern());
        $this->Nama_Mahasiswa->CurrentValue = $this->Nama_Mahasiswa->FormValue;
        $this->Pejabat->CurrentValue = $this->Pejabat->FormValue;
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
        $this->Nomor_Surat->setDbValue($row['Nomor_Surat']);
        $this->Tanggal_Surat->setDbValue($row['Tanggal_Surat']);
        $this->Nama_Mahasiswa->setDbValue($row['Nama_Mahasiswa']);
        $this->Pejabat->setDbValue($row['Pejabat']);
        $this->Arsip_Surat->Upload->DbValue = $row['Arsip_Surat'];
        $this->Arsip_Surat->setDbValue($this->Arsip_Surat->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow(): array
    {
        $row = [];
        $row['Nomor_Surat'] = $this->Nomor_Surat->DefaultValue;
        $row['Tanggal_Surat'] = $this->Tanggal_Surat->DefaultValue;
        $row['Nama_Mahasiswa'] = $this->Nama_Mahasiswa->DefaultValue;
        $row['Pejabat'] = $this->Pejabat->DefaultValue;
        $row['Arsip_Surat'] = $this->Arsip_Surat->DefaultValue;
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

        // Nomor_Surat
        $this->Nomor_Surat->RowCssClass = "row";

        // Tanggal_Surat
        $this->Tanggal_Surat->RowCssClass = "row";

        // Nama_Mahasiswa
        $this->Nama_Mahasiswa->RowCssClass = "row";

        // Pejabat
        $this->Pejabat->RowCssClass = "row";

        // Arsip_Surat
        $this->Arsip_Surat->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // Nomor_Surat
            $this->Nomor_Surat->ViewValue = $this->Nomor_Surat->CurrentValue;

            // Tanggal_Surat
            $this->Tanggal_Surat->ViewValue = $this->Tanggal_Surat->CurrentValue;
            $this->Tanggal_Surat->ViewValue = FormatDateTime($this->Tanggal_Surat->ViewValue, $this->Tanggal_Surat->formatPattern());

            // Nama_Mahasiswa
            $curVal = strval($this->Nama_Mahasiswa->CurrentValue);
            if ($curVal != "") {
                $this->Nama_Mahasiswa->ViewValue = $this->Nama_Mahasiswa->lookupCacheOption($curVal);
                if ($this->Nama_Mahasiswa->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->Nama_Mahasiswa->Lookup->getTable()->Fields["Nim"]->searchExpression(), "=", $curVal, $this->Nama_Mahasiswa->Lookup->getTable()->Fields["Nim"]->searchDataType(), "DB");
                    $sqlWrk = $this->Nama_Mahasiswa->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Nama_Mahasiswa->Lookup->renderViewRow($rswrk[0]);
                        $this->Nama_Mahasiswa->ViewValue = $this->Nama_Mahasiswa->displayValue($arwrk);
                    } else {
                        $this->Nama_Mahasiswa->ViewValue = $this->Nama_Mahasiswa->CurrentValue;
                    }
                }
            } else {
                $this->Nama_Mahasiswa->ViewValue = null;
            }

            // Pejabat
            $curVal = strval($this->Pejabat->CurrentValue);
            if ($curVal != "") {
                $this->Pejabat->ViewValue = $this->Pejabat->lookupCacheOption($curVal);
                if ($this->Pejabat->ViewValue === null) { // Lookup from database
                    $filterWrk = SearchFilter($this->Pejabat->Lookup->getTable()->Fields["Nip"]->searchExpression(), "=", $curVal, $this->Pejabat->Lookup->getTable()->Fields["Nip"]->searchDataType(), "DB");
                    $sqlWrk = $this->Pejabat->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->Pejabat->Lookup->renderViewRow($rswrk[0]);
                        $this->Pejabat->ViewValue = $this->Pejabat->displayValue($arwrk);
                    } else {
                        $this->Pejabat->ViewValue = $this->Pejabat->CurrentValue;
                    }
                }
            } else {
                $this->Pejabat->ViewValue = null;
            }

            // Arsip_Surat
            if (!IsEmpty($this->Arsip_Surat->Upload->DbValue)) {
                $this->Arsip_Surat->ViewValue = $this->Arsip_Surat->Upload->DbValue;
            } else {
                $this->Arsip_Surat->ViewValue = "";
            }

            // Nomor_Surat
            if (!IsEmpty($this->Nomor_Surat->CurrentValue)) {
                $this->Nomor_Surat->HrefValue = $this->Nomor_Surat->getLinkPrefix() . $this->Nomor_Surat->CurrentValue; // Add prefix/suffix
                $this->Nomor_Surat->LinkAttrs["target"] = "_parent"; // Add target
                if ($this->isExport()) {
                    $this->Nomor_Surat->HrefValue = FullUrl($this->Nomor_Surat->HrefValue, "href");
                }
            } else {
                $this->Nomor_Surat->HrefValue = "";
            }

            // Tanggal_Surat
            $this->Tanggal_Surat->HrefValue = "";

            // Nama_Mahasiswa
            $this->Nama_Mahasiswa->HrefValue = "";

            // Pejabat
            $this->Pejabat->HrefValue = "";

            // Arsip_Surat
            $this->Arsip_Surat->HrefValue = "";
            $this->Arsip_Surat->ExportHrefValue = $this->Arsip_Surat->UploadPath . $this->Arsip_Surat->Upload->DbValue;
        } elseif ($this->RowType == RowType::ADD) {
            // Nomor_Surat
            $this->Nomor_Surat->setupEditAttributes();
            if (!$this->Nomor_Surat->Raw) {
                $this->Nomor_Surat->CurrentValue = HtmlDecode($this->Nomor_Surat->CurrentValue);
            }
            $this->Nomor_Surat->EditValue = HtmlEncode($this->Nomor_Surat->CurrentValue);
            $this->Nomor_Surat->PlaceHolder = RemoveHtml($this->Nomor_Surat->caption());

            // Tanggal_Surat
            $this->Tanggal_Surat->setupEditAttributes();
            $this->Tanggal_Surat->EditValue = HtmlEncode(FormatDateTime($this->Tanggal_Surat->CurrentValue, $this->Tanggal_Surat->formatPattern()));
            $this->Tanggal_Surat->PlaceHolder = RemoveHtml($this->Tanggal_Surat->caption());

            // Nama_Mahasiswa
            $this->Nama_Mahasiswa->setupEditAttributes();
            $curVal = trim(strval($this->Nama_Mahasiswa->CurrentValue));
            if ($curVal != "") {
                $this->Nama_Mahasiswa->ViewValue = $this->Nama_Mahasiswa->lookupCacheOption($curVal);
            } else {
                $this->Nama_Mahasiswa->ViewValue = $this->Nama_Mahasiswa->Lookup !== null && is_array($this->Nama_Mahasiswa->lookupOptions()) && count($this->Nama_Mahasiswa->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Nama_Mahasiswa->ViewValue !== null) { // Load from cache
                $this->Nama_Mahasiswa->EditValue = array_values($this->Nama_Mahasiswa->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Nama_Mahasiswa->Lookup->getTable()->Fields["Nim"]->searchExpression(), "=", $this->Nama_Mahasiswa->CurrentValue, $this->Nama_Mahasiswa->Lookup->getTable()->Fields["Nim"]->searchDataType(), "DB");
                }
                $sqlWrk = $this->Nama_Mahasiswa->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Nama_Mahasiswa->EditValue = $arwrk;
            }
            $this->Nama_Mahasiswa->PlaceHolder = RemoveHtml($this->Nama_Mahasiswa->caption());

            // Pejabat
            $this->Pejabat->setupEditAttributes();
            $curVal = trim(strval($this->Pejabat->CurrentValue));
            if ($curVal != "") {
                $this->Pejabat->ViewValue = $this->Pejabat->lookupCacheOption($curVal);
            } else {
                $this->Pejabat->ViewValue = $this->Pejabat->Lookup !== null && is_array($this->Pejabat->lookupOptions()) && count($this->Pejabat->lookupOptions()) > 0 ? $curVal : null;
            }
            if ($this->Pejabat->ViewValue !== null) { // Load from cache
                $this->Pejabat->EditValue = array_values($this->Pejabat->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = SearchFilter($this->Pejabat->Lookup->getTable()->Fields["Nip"]->searchExpression(), "=", $this->Pejabat->CurrentValue, $this->Pejabat->Lookup->getTable()->Fields["Nip"]->searchDataType(), "DB");
                }
                $sqlWrk = $this->Pejabat->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->cacheProfile)->fetchAllAssociative();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->Pejabat->EditValue = $arwrk;
            }
            $this->Pejabat->PlaceHolder = RemoveHtml($this->Pejabat->caption());

            // Arsip_Surat
            $this->Arsip_Surat->setupEditAttributes();
            if (!IsEmpty($this->Arsip_Surat->Upload->DbValue)) {
                $this->Arsip_Surat->EditValue = $this->Arsip_Surat->Upload->DbValue;
            } else {
                $this->Arsip_Surat->EditValue = "";
            }
            if (!IsEmpty($this->Arsip_Surat->CurrentValue)) {
                $this->Arsip_Surat->Upload->FileName = $this->Arsip_Surat->CurrentValue;
            }
            if (!Config("CREATE_UPLOAD_FILE_ON_COPY")) {
                $this->Arsip_Surat->Upload->DbValue = null;
            }
            if ($this->isShow() || $this->isCopy()) {
                $this->Arsip_Surat->Upload->setupTempDirectory();
            }

            // Add refer script

            // Nomor_Surat
            if (!IsEmpty($this->Nomor_Surat->CurrentValue)) {
                $this->Nomor_Surat->HrefValue = $this->Nomor_Surat->getLinkPrefix() . $this->Nomor_Surat->CurrentValue; // Add prefix/suffix
                $this->Nomor_Surat->LinkAttrs["target"] = "_parent"; // Add target
                if ($this->isExport()) {
                    $this->Nomor_Surat->HrefValue = FullUrl($this->Nomor_Surat->HrefValue, "href");
                }
            } else {
                $this->Nomor_Surat->HrefValue = "";
            }

            // Tanggal_Surat
            $this->Tanggal_Surat->HrefValue = "";

            // Nama_Mahasiswa
            $this->Nama_Mahasiswa->HrefValue = "";

            // Pejabat
            $this->Pejabat->HrefValue = "";

            // Arsip_Surat
            $this->Arsip_Surat->HrefValue = "";
            $this->Arsip_Surat->ExportHrefValue = $this->Arsip_Surat->UploadPath . $this->Arsip_Surat->Upload->DbValue;
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
            if ($this->Nomor_Surat->Visible && $this->Nomor_Surat->Required) {
                if (!$this->Nomor_Surat->IsDetailKey && IsEmpty($this->Nomor_Surat->FormValue)) {
                    $this->Nomor_Surat->addErrorMessage(str_replace("%s", $this->Nomor_Surat->caption(), $this->Nomor_Surat->RequiredErrorMessage));
                }
            }
            if ($this->Tanggal_Surat->Visible && $this->Tanggal_Surat->Required) {
                if (!$this->Tanggal_Surat->IsDetailKey && IsEmpty($this->Tanggal_Surat->FormValue)) {
                    $this->Tanggal_Surat->addErrorMessage(str_replace("%s", $this->Tanggal_Surat->caption(), $this->Tanggal_Surat->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->Tanggal_Surat->FormValue, $this->Tanggal_Surat->formatPattern())) {
                $this->Tanggal_Surat->addErrorMessage($this->Tanggal_Surat->getErrorMessage(false));
            }
            if ($this->Nama_Mahasiswa->Visible && $this->Nama_Mahasiswa->Required) {
                if (!$this->Nama_Mahasiswa->IsDetailKey && IsEmpty($this->Nama_Mahasiswa->FormValue)) {
                    $this->Nama_Mahasiswa->addErrorMessage(str_replace("%s", $this->Nama_Mahasiswa->caption(), $this->Nama_Mahasiswa->RequiredErrorMessage));
                }
            }
            if ($this->Pejabat->Visible && $this->Pejabat->Required) {
                if (!$this->Pejabat->IsDetailKey && IsEmpty($this->Pejabat->FormValue)) {
                    $this->Pejabat->addErrorMessage(str_replace("%s", $this->Pejabat->caption(), $this->Pejabat->RequiredErrorMessage));
                }
            }
            if ($this->Arsip_Surat->Visible && $this->Arsip_Surat->Required) {
                if ($this->Arsip_Surat->Upload->FileName == "" && !$this->Arsip_Surat->Upload->KeepFile) {
                    $this->Arsip_Surat->addErrorMessage(str_replace("%s", $this->Arsip_Surat->caption(), $this->Arsip_Surat->RequiredErrorMessage));
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

    // Add record
    protected function addRow(?array $oldRow = null): bool
    {
        // Get new row
        $newRow = $this->getAddRow();
        if ($this->Arsip_Surat->Visible && !$this->Arsip_Surat->Upload->KeepFile) {
            if (!IsEmpty($this->Arsip_Surat->Upload->FileName)) {
                $this->Arsip_Surat->Upload->DbValue = null;
                FixUploadFileNames($this->Arsip_Surat);
                $this->Arsip_Surat->setDbValueDef($newRow, $this->Arsip_Surat->Upload->FileName, false);
            }
        }

        // Update current values
        $this->Fields->setCurrentValues($newRow);
        if ($this->Nomor_Surat->CurrentValue != "") { // Check field with unique index
            $filter = "(`Nomor_Surat` = '" . AdjustSql($this->Nomor_Surat->CurrentValue) . "')";
            $rsChk = $this->loadRs($filter)->fetchAssociative();
            if ($rsChk !== false) {
                $idxErrMsg = sprintf($this->language->phrase("DuplicateIndex"), $this->Nomor_Surat->CurrentValue, $this->Nomor_Surat->caption());
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($oldRow);

        // Call Row Inserting event
        $insertRow = $this->rowInserting($oldRow, $newRow);

        // Check if key value entered
        if ($insertRow && $this->ValidateKey && strval($newRow['Nomor_Surat']) == "") {
            $this->setFailureMessage($this->language->phrase("InvalidKeyValue"));
            $insertRow = false;
        }

        // Check for duplicate key
        if ($insertRow && $this->ValidateKey) {
            $filter = $this->getRecordFilter($newRow);
            $rsChk = $this->loadRs($filter)->fetchAssociative();
            if ($rsChk !== false) {
                $keyErrMsg = sprintf($this->language->phrase("DuplicateKey"), $filter);
                $this->setFailureMessage($keyErrMsg);
                $insertRow = false;
            }
        }
        if ($insertRow) {
            $addRow = $this->insert($newRow);
            if ($addRow) {
                if ($this->Arsip_Surat->Visible && !$this->Arsip_Surat->Upload->KeepFile) {
                    $this->Arsip_Surat->Upload->DbValue = null;
                    if (!SaveUploadFiles($this->Arsip_Surat, $newRow['Arsip_Surat'], false)) {
                        $this->setFailureMessage($this->language->phrase("UploadError7"));
                        return false;
                    }
                }
            } elseif (!IsEmpty($this->DbErrorMessage)) { // Show database error
                $this->setFailureMessage($this->DbErrorMessage);
            }
        } else {
            if ($this->peekSuccessMessage() || $this->peekFailureMessage()) {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($this->language->phrase("InsertCancelled"));
            }
            $addRow = $insertRow;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($oldRow, $newRow);
        }

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRecordsFromResult([$newRow], true);
            $table = $this->TableVar;
            WriteJson(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow;
    }

    /**
     * Get add row
     *
     * @return array
     */
    protected function getAddRow(): array
    {
        $newRow = [];

        // Nomor_Surat
        $this->Nomor_Surat->setDbValueDef($newRow, $this->Nomor_Surat->CurrentValue, false);

        // Tanggal_Surat
        $this->Tanggal_Surat->setDbValueDef($newRow, UnFormatDateTime($this->Tanggal_Surat->CurrentValue, $this->Tanggal_Surat->formatPattern()), false);

        // Nama_Mahasiswa
        $this->Nama_Mahasiswa->setDbValueDef($newRow, $this->Nama_Mahasiswa->CurrentValue, false);

        // Pejabat
        $this->Pejabat->setDbValueDef($newRow, $this->Pejabat->CurrentValue, false);

        // Arsip_Surat
        if ($this->Arsip_Surat->Visible && !$this->Arsip_Surat->Upload->KeepFile) {
            if ($this->Arsip_Surat->Upload->FileName == "") {
                $newRow['Arsip_Surat'] = null;
            } else {
                FixUploadTempFileNames($this->Arsip_Surat);
                $newRow['Arsip_Surat'] = $this->Arsip_Surat->Upload->FileName;
            }
        }
        return $newRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb(): void
    {
        $breadcrumb = Breadcrumb();
        $url = CurrentUrl();
        $breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("suratketeranganlist"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $breadcrumb->add("add", $pageId, $url);
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
                case "x_Nama_Mahasiswa":
                    break;
                case "x_Pejabat":
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
