<?php
/**
 * Documentation page template
 *
 * @since      1.0.0
 *
 * @package    Ultrapress
 * @subpackage Ultrapress/admin/partials
 */
?>
<div class="wrap ultrapress-documentation">
    <h1>UltraPress Documentation</h1>
    
    <!-- Introduction -->
    <section class="doc-section">
        <h2>Introduction</h2>
        <p>Welcome to UltraPress - a powerful WordPress plugin for creating automated workflows and circuits. This documentation will guide you through all aspects of using UltraPress effectively.</p>
        
        <div class="ultrapress-video-container">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/mQlrCUliSTM?si=6711Gb5TejIwa6sa" title="UltraPress Tutorial" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
    </section>

    <!-- Getting Started -->
    <section class="doc-section">
        <h2>Getting Started</h2>
        <h3>System Requirements</h3>
        <ul>
            <li>WordPress 5.0 or higher</li>
            <li>PHP 7.4 or higher</li>
            <li>MySQL 5.6 or higher</li>
            <li>Modern web browser (Chrome, Firefox, Safari, Edge)</li>
        </ul>

        <h3>Installation</h3>
        <ol>
            <li>Upload the UltraPress plugin to your WordPress plugins directory</li>
            <li>Activate the plugin through the WordPress admin panel</li>
            <li>Navigate to UltraPress in your WordPress admin menu</li>
        </ol>
    </section>

    <!-- Core Concepts -->
    <section class="doc-section">
        <h2>Core Concepts</h2>
        
        <h3>Components</h3>
        <p>Components are the building blocks of your workflows. Each component performs a specific function:</p>
        <ul>
            <li><strong>Trigger Components:</strong> Start your workflows (e.g., Webhooks, Schedules, Form Submissions)</li>
            <li><strong>Action Components:</strong> Perform specific tasks (e.g., Send Email, Create Post, API Request)</li>
            <li><strong>Logic Components:</strong> Make decisions based on conditions</li>
            <li><strong>Data Components:</strong> Transform and manipulate data</li>
            <li><strong>Integration Components:</strong> Connect with external services</li>
        </ul>

        <h3>Connections</h3>
        <p>Connections define how data flows between components:</p>
        <ul>
            <li><strong>Output Nodes (Red):</strong> Send data to the next component</li>
            <li><strong>Input Nodes (Green):</strong> Receive data from previous components</li>
            <li><strong>Success Path:</strong> Executed when a component completes successfully</li>
            <li><strong>Failure Path:</strong> Executed when a component encounters an error</li>
        </ul>
    </section>

    <!-- Building Workflows -->
    <section class="doc-section">
        <h2>Building Workflows</h2>

        <h3>Creating a New Circuit</h3>
        <ol>
            <li>Click "Add New Circuit" in the UltraPress dashboard</li>
            <li>Double-click anywhere in the workspace to add components</li>
            <li>Configure each component by clicking on it and setting its parameters</li>
            <li>Connect components by dragging from output nodes to input nodes</li>
            <li>Save your circuit with a descriptive name</li>
        </ol>

        <h3>Component Configuration</h3>
        <p>Each component has specific settings that control its behavior:</p>
        <ul>
            <li><strong>Basic Settings:</strong> Name, description, and core functionality</li>
            <li><strong>Input Parameters:</strong> Configure how the component processes incoming data</li>
            <li><strong>Output Mapping:</strong> Define how processed data is passed to next components</li>
            <li><strong>Error Handling:</strong> Specify behavior when errors occur</li>
        </ul>
    </section>

    <!-- Advanced Features -->
    <section class="doc-section">
        <h2>Advanced Features</h2>

        <h3>Packages</h3>
        <p>Create reusable workflow packages:</p>
        <ul>
            <li>Export circuits as packages for reuse</li>
            <li>Import packages into other workflows</li>
            <li>Version control your packages</li>
            <li>Share packages across different WordPress installations</li>
        </ul>

        <h3>Debug Mode</h3>
        <p>Troubleshoot your workflows effectively:</p>
        <ul>
            <li>Enable detailed logging</li>
            <li>Track data flow between components</li>
            <li>Monitor execution times</li>
            <li>Identify and fix errors</li>
        </ul>

        <h3>Performance Optimization</h3>
        <ul>
            <li>Use caching for repetitive operations</li>
            <li>Implement cleanup routines</li>
            <li>Optimize database queries</li>
            <li>Handle large data sets efficiently</li>
        </ul>
    </section>

    <!-- Best Practices -->
    <section class="doc-section">
        <h2>Best Practices</h2>
        
        <h3>Workflow Design</h3>
        <ul>
            <li>Plan your workflow before building</li>
            <li>Use descriptive names for components and circuits</li>
            <li>Implement proper error handling</li>
            <li>Test thoroughly before deployment</li>
            <li>Document your workflows</li>
        </ul>

        <h3>Security</h3>
        <ul>
            <li>Secure API credentials properly</li>
            <li>Implement access controls</li>
            <li>Validate input data</li>
            <li>Regular security audits</li>
        </ul>
    </section>

    <!-- Troubleshooting -->
    <section class="doc-section">
        <h2>Troubleshooting</h2>
        
        <h3>Common Issues</h3>
        <ul>
            <li><strong>Component Not Executing:</strong> Check connections and configuration</li>
            <li><strong>Data Not Flowing:</strong> Verify input/output mappings</li>
            <li><strong>Performance Issues:</strong> Review optimization guidelines</li>
            <li><strong>Integration Errors:</strong> Validate API credentials and endpoints</li>
        </ul>

        <h3>Support Resources</h3>
        <ul>
            <li>Error logs in WordPress debug.log</li>
            <li>UltraPress debug panel</li>
            <li>Component-specific documentation</li>
        </ul>
    </section>

    <!-- API Documentation -->
    <section class="doc-section">
        <h2>API Documentation</h2>
        <p>UltraPress provides extensive APIs for extending functionality:</p>
        <ul>
            <li>Component Development API</li>
            <li>Workflow Management API</li>
            <li>Data Processing API</li>
            <li>Integration Hooks and Filters</li>
        </ul>
    </section>

    <!-- Additional Resources -->
    <section class="doc-section">
        <h2>Additional Resources</h2>
        <p>For more detailed information and updates:</p>
        <ul>
            <li><a href="https://drive.google.com/file/d/17IrXe726QXmoYC-dVpAVsVs-U_nTxaX9/view?usp=sharing" target="_blank">Complete User Guide (PDF)</a></li>
            <li>Video Tutorials</li>
            <li>Sample Workflows</li>
            <li>Community Forums</li>
        </ul>
    </section>
</div>

<style>
.ultrapress-documentation {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    font-size: 15px;
    line-height: 1.6;
}

.doc-section {
    margin-bottom: 40px;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.doc-section h2 {
    color: #2271b1;
    border-bottom: 2px solid #2271b1;
    padding-bottom: 10px;
    margin-bottom: 20px;
}

.doc-section h3 {
    color: #1d2327;
    margin: 25px 0 15px;
}

.doc-section ul, .doc-section ol {
    margin-left: 20px;
}

.doc-section li {
    margin-bottom: 8px;
}

.doc-section a {
    color: #2271b1;
    text-decoration: none;
}

.doc-section a:hover {
    text-decoration: underline;
}
</style>
